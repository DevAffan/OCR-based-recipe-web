<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use thiagoalessio\TesseractOCR\TesseractOCR;


class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $recipes = Recipe::paginate(10);
        return view('recipe' , compact('recipes'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('recipe_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $input = $request->validate([
            'name' => 'required | min:5 | max:225',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
        $recipe = new Recipe();
        if(request('image')){
            $input['image'] = $request->file('image')->store('public/images');
            $recipe->image = $input['image'];
            $ocr = new TesseractOCR();
            $ocr->image($request->image);
            $scan_img = $ocr->run();
            $recipe->image_text = $scan_img;

            //length of string
            $length = strlen($scan_img);
            $recipe->length = $length;


            // Initialise our character count array
            $chars = array();

            // Read in the file and adjust the counts
            $array = explode(' ', $scan_img);
            $handle = count($array);
            $charcount = 0;

            for($i = 0 ; $i < $handle ; $i++){
                $thischar = $array[$i];
                    if (!isset($chars[ord($thischar)])) {
                        $chars[ord($thischar)] = 0;
                    }
                    $chars[ord($thischar)]++;
                    $charcount++;
            }
            // Next calculate the entropy
            $entropy = 0.0;
            foreach ($chars as $val) {
                $p = $val / $charcount;
                $entropy = $entropy - ($p * log($p,2));
            }

            $recipe->entropy = $entropy;

            if($length != 0 && $entropy != 0){

            //length/entropy

            $len_entro = $length/$entropy;
            $recipe->len_entro = $len_entro;
            //Entropy/length

            $entro_len = $entropy/$length;
            // dd($entro_len);
            $recipe->entro_len = $entro_len;
            }

            else
            {

            $recipe->len_entro = 0;
            $recipe->entro_len = 0;

            }
        }
        $recipe->name = $input['name'];
        $recipe->save();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $recipe = Recipe::findorfail($id);
        return view('recipe_show' , compact('recipe'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        // dd($id);
        $recipe = Recipe::findorfail($id);

        return view('recipe_edit' , compact('recipe'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if($request->image){
            $input = $request->validate([
                'name' => 'required | min:5 | max:225',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            ]);
        }
        else{
            $input = $request->validate([
                'name' => 'required | min:5 | max:225',
                'image_text' => 'required',
            ]);
        }
// dd($input);
        $recipe = Recipe::findorfail($id);

        if(request('image')){

            $input['image'] = $request->file('image')->store('public/images');

            $recipe->image = $input['image'];

            $ocr = new TesseractOCR();
            $ocr->image($request->image);
            $scan_img = $ocr->run();
            $recipe->image_text = $scan_img;


            // Initialise our character count array
            $chars = array();

            // Read in the file and adjust the counts
            $array = explode(' ', $scan_img);
            $handle = count($array);

            $charcount = 0;

            for($i = 0 ; $i < $handle ; $i++){
                $thischar = $array[$i];
                    if (!isset($chars[ord($thischar)])) {
                        $chars[ord($thischar)] = 0;
                    }
                    $chars[ord($thischar)]++;
                    $charcount++;
            }

            // Next calculate the entropy
            $entropy = 0.0;
            foreach ($chars as $val) {
                $p = $val / $charcount;
                $entropy = $entropy - ($p * log($p,2));
            }

            $recipe->entropy = $entropy;

        }

        else{
        $recipe->image_text = $input['image_text'];
        }

        $recipe->name = $input['name'];
        $recipe->save();

        return redirect()->route('recipe.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Recipe::findorfail($id)->delete();
        Session::flash('message', 'Item has been Deleted');
        return back();
    }

    public function search_page(Request $request){

        $recipes=Recipe::where('name','LIKE','%'.$request->search."%")->get();


        return view('search' , compact('recipes'));


    }



    public function search(Request $request){


        if($request->ajax())
        {
        $output="";
        $recipes=Recipe::where('name','LIKE','%'.$request->search."%")->get();
        if($recipes)
        {
        foreach ($recipes as $key => $recipe)
        {
        $output.='<tr>'.
            '<td>'.$recipe->id.'</td>'.
            '<td>'.$recipe->name.'</td>'.
            '<td>'.$recipe->image.'</td>'.
            // '<td><a style="text-decoration: none" href="{{route("recipe.show" , '.$recipe->id.')}}">{{ Str::limit('.$recipe->image_text.', 50) }}</a></td>'.
            '<td>'.$recipe->image_text.'</td>'.

            '<td>'.$recipe->length.'</td>'.
            '<td>'.$recipe->entropy.'</td>'.
            '<td>'.$recipe->updated_at.'</td>'.
            '<td>'.$recipe->created_at.'</td>'.
            '</tr>';
        }
        return Response($output);

    }
}

    }

}
