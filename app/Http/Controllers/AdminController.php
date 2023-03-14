<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Page;
use App\Models\Link;
use App\Models\View;

class AdminController extends Controller
{
    public function __construct(){
        $this->middleware('auth');

    }

    public function index() {
        $user = Auth::user();

        $pages = Page::where('id_user', $user->id)->get();
        return view('panel/index', [
        'pages' => $pages
        ]);
    }
    public function npage(Request $request){
        return view('panel/npage', [
            'error' => $request->session()->get('error')
        ]);
    }

        public function npageAction(Request $request){
            $creds = $request->validate([


                'slug' =>  ['required', 'regex:/^[0-9a-zA-Z]+$/u']



            ]);


            $creds = $request->only('id_user', 'slug');


            $hasPage = Page::where('slug', $creds['slug'])->count();
        //Só posso cadastrar se não tiver tem que ser igual a === 0
        if($hasPage === 0){
            //se não houver slug cadastrado prosseguir com cadastro aqui no if
            $page = new Page();

            $page->id_user = Auth::user()->id;
            $page->slug = $creds['slug'];




            $page->save();

            return redirect('/panel');

        }else{
            //erro caso já tenha slug no banco de dados
            $request->session()->flash('error', 'já existe essa pagina ');
            return redirect('panel/npage');

        }
        }

        public function pageLinks($slug){

            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){
                $links = Link::where('id_page', $page->id)
                ->orderBy('order', 'ASC')
                ->get();
                return view('panel/page_links', [
                    'menu' => 'links',
                    'page' => $page,
                    'links' => $links
                ]);
            }else{
                return redirect('/panel');
            }

        }
        public function linkOrderUpdate($linkid, $pos){
            $user = Auth::user();
            // verificar se o link pertence a uma pagina do usuario logado
            //logica para trocar o order no banco de dados
            // verificar se subiu ou desceu
            // se subiu:
            // - jogar os proximos itens para baixo

            // -se desceu:
            //      - jogar os itens anteriores para cima
                      // - substituo o item que quero mudar
            // - : final atualizo todos os links

            $link = Link::find($linkid);

            $myPages = [];
            $myPagesQuery = Page::where('id_user', $user->id)->get();
            foreach($myPagesQuery as $pageItem){
                $myPages [] = $pageItem->id;
            }


                if(in_array($link->id_page, $myPages)){

                    if($link->order > $pos){
                        //subiu item
                        //jogando os proximos para baixo
                        $afterLinks = Link::where('id_page', $link->id_page)
                        ->where('order', '>=', $pos)
                        ->get();
                        foreach($afterLinks as $afterLink){
                         $afterLink->order++;
                         $afterLink->save();
                        }
                    }elseif($link->order < $pos){
                        //desceu item
                        //jogando os anteriores para cima
                        $beforeLinks = Link::where('id_page', $link->id_page)
                        ->where('order', '<=', $pos)
                        ->get();
                        foreach($beforeLinks as $beforeLink){
                            $beforeLink->order--;
                            $beforeLink->save();
                        }
                    }

                    //posicionando o item
                    $link->order = $pos;
                    $link->save();

                    //corrigindo as posições
                    $allLinks = Link::where('id_page', $link->id_page)
                        ->orderBy('order', 'ASC')
                        ->get();
                        foreach($allLinks as $linkKey => $linkItem ){
                            $linkItem->order = $linkKey;
                            $linkItem->save();
                        }
                }

            return [];

        }
        public function newLink($slug){
            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){
                return view('panel/page_editlink', [
                    'menu' => 'links',
                    'page' => $page

                ]);
            }else{
                return redirect('/panel');
            }
        }

        public function newLinkAction($slug, Request $request){


            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){

                $fields = $request->validate([

                    'status' => ['required', 'boolean'],
                    'title' => ['required', 'min:2'],
                    'op_link_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:18048',
                    'href' => ['required', 'url'],
                    'op_bg_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                    'op_text_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                    'op_border_type' => ['required', Rule::in(['square', 'rounded'])]

                ]);
                if ($image = $request->file('op_link_image')) {
                    $destinationPath = 'media/uploads';
                    $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $profileImage);
                    $input['op_link_image'] = "$profileImage";
                }
                $totalLinks = Link::where('id_page', $page->id)->count();

                $newLink = new Link();
                $newLink->id_page = $page->id;
                $newLink->op_link_image = $input['op_link_image']?? '';
                $newLink->status = $fields['status'];
                $newLink->order = $totalLinks;
                $newLink->title = $fields['title'];
                $newLink->href = $fields['href'];
                $newLink->op_bg_color = $fields['op_bg_color'];
                $newLink->op_text_color = $fields['op_text_color'];
                $newLink->op_border_type = $fields['op_border_type'];
                $newLink->save();

                return redirect('/panel/'.$page->slug.'/links');



            }else{
                return redirect('/panel');
            }
        }
               //Nova implementação criação de link com video
        public function newVideo($slug){
            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){
                return view('panel/page_editvideo', [
                    'menu' => 'links',
                    'page' => $page

                ]);
            }else{
                return redirect('/panel');
            }
        }

        public function newVideoAction($slug, Request $request){


            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){

                $fields = $request->validate([

                    'status' => ['required', 'boolean'],
                    'title' => ['required', 'min:2'],
                    'op_video' =>  'required|file|mimes:mp4',

                    'href' => ['required', 'url'],
                    'op_bg_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                    'op_text_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                    'op_border_type' => ['required', Rule::in(['square', 'rounded'])]

                ]);
                if ($image = $request->file('op_video')) {
                    $destinationPath = 'media/uploads';
                    $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $profileImage);
                    $input['op_video'] = "$profileImage";
                }
                $totalLinks = Link::where('id_page', $page->id)->count();

                $newLink = new Link();
                $newLink->id_page = $page->id;
                $newLink->op_video = $input['op_video'];
                $newLink->status = $fields['status'];
                $newLink->order = $totalLinks;
                $newLink->title = $fields['title'];
                $newLink->href = $fields['href'];
                $newLink->op_bg_color = $fields['op_bg_color'];
                $newLink->op_text_color = $fields['op_text_color'];
                $newLink->op_border_type = $fields['op_border_type'];
                $newLink->save();

                return redirect('/panel/'.$page->slug.'/links');



            }else{
                return redirect('/panel');
            }
        }

         //Fim implementação criação de link com video

         //nova implementação edição de video
         public function editVideo($slug, $linkid){
            $user = Auth::user();
             //proteção
             $page = Page::where('slug', $slug)
             ->where('id_user', $user->id)->first();
             if($page){
                 $link = Link::where('id_page', $page->id)
                 ->where('id', $linkid)
                 ->first();

                 if($link){
                     return view('panel/page_editvideo', [
                         'menu' => 'links',
                         'page' => $page,
                         'link' => $link
                     ]);

                 }
             }
             return redirect('/panel');
        }
        public function editVideoAction($slug, $linkid, Request $request){
            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){
                $link = Link::where('id_page', $page->id)
                ->where('id', $linkid)
                ->first();

                if($link){
                    $fields = $request->validate([

                        'status' => ['required', 'boolean'],
                        'title' => ['required', 'min:2'],
                        'op_video' =>  'required|file|mimes:mp4',

                        'href' => ['required', 'url'],
                        'op_bg_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                        'op_text_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                        'op_border_type' => ['required', Rule::in(['square', 'rounded'])]

                    ]);
                    if ($image = $request->file('op_video')) {
                        $destinationPath = 'media/uploads';
                        $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($destinationPath, $profileImage);
                        $input['op_video'] = "$profileImage";
                    }

                    $link->op_video = $input['op_video'];
                    $link->status = $fields['status'];

                    $link->title = $fields['title'];
                    $link->href = $fields['href'];
                    $link->op_bg_color = $fields['op_bg_color'];
                    $link->op_text_color = $fields['op_text_color'];
                    $link->op_border_type = $fields['op_border_type'];
                    $link->save();
                    return redirect('/panel/'.$page->slug.'/links');

                }
            }
            return redirect('/panel');
        }
        //fim edição de video
         //NEW AUDIO

        public function newAudio($slug){
            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){
                return view('panel/page_editaudio', [
                    'menu' => 'links',
                    'page' => $page

                ]);
            }else{
                return redirect('/panel');
            }
        }

        public function newAudioAction($slug, Request $request){


            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){

                $fields = $request->validate([

                    'status' => ['required', 'boolean'],
                    'title' => ['required', 'min:2'],
                    'op_audio' => 'required|file|mimes:mp3,mp4,wav,mid',



                    'href' => ['required', 'url'],
                    'op_bg_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                    'op_text_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                    'op_border_type' => ['required', Rule::in(['square', 'rounded'])]

                ]);
                if ($image = $request->file('op_audio')) {
                    $destinationPath = 'media/uploads';
                    $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $profileImage);
                    $input['op_audio'] = "$profileImage";
                }
                $totalLinks = Link::where('id_page', $page->id)->count();

                $newLink = new Link();
                $newLink->id_page = $page->id;
                $newLink->op_audio = $input['op_audio'];
                $newLink->status = $fields['status'];
                $newLink->order = $totalLinks;
                $newLink->title = $fields['title'];
                $newLink->href = $fields['href'];
                $newLink->op_bg_color = $fields['op_bg_color'];
                $newLink->op_text_color = $fields['op_text_color'];
                $newLink->op_border_type = $fields['op_border_type'];
                $newLink->save();

                return redirect('/panel/'.$page->slug.'/links');



            }else{
                return redirect('/panel');
            }
        }


        //INICIO EDIT AUDIO
        public function editAudio($slug, $linkid){
            $user = Auth::user();
             //proteção
             $page = Page::where('slug', $slug)
             ->where('id_user', $user->id)->first();
             if($page){
                 $link = Link::where('id_page', $page->id)
                 ->where('id', $linkid)
                 ->first();

                 if($link){
                     return view('panel/page_editaudio', [
                         'menu' => 'links',
                         'page' => $page,
                         'link' => $link
                     ]);

                 }
             }
             return redirect('/panel');
        }
        public function editAudioAction($slug, $linkid, Request $request){
            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){
                $link = Link::where('id_page', $page->id)
                ->where('id', $linkid)
                ->first();

                if($link){
                    $fields = $request->validate([

                        'status' => ['required', 'boolean'],
                        'title' => ['required', 'min:2'],
                        'op_audio' => 'required|file|mimes:mp3,mp4,wav,mid',

                        'href' => ['required', 'url'],
                        'op_bg_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                        'op_text_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                        'op_border_type' => ['required', Rule::in(['square', 'rounded'])]

                    ]);
                    if ($image = $request->file('op_audio')) {
                        $destinationPath = 'media/uploads';
                        $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($destinationPath, $profileImage);
                        $input['op_audio'] = "$profileImage";
                    }

                    $link->op_audio = $input['op_audio'];
                    $link->status = $fields['status'];

                    $link->title = $fields['title'];
                    $link->href = $fields['href'];
                    $link->op_bg_color = $fields['op_bg_color'];
                    $link->op_text_color = $fields['op_text_color'];
                    $link->op_border_type = $fields['op_border_type'];
                    $link->save();
                    return redirect('/panel/'.$page->slug.'/links');

                }
            }
            return redirect('/panel');
        }


        //fim audio func

        public function editLink($slug, $linkid){
            $user = Auth::user();
             //proteção
             $page = Page::where('slug', $slug)
             ->where('id_user', $user->id)->first();
             if($page){
                 $link = Link::where('id_page', $page->id)
                 ->where('id', $linkid)
                 ->first();

                 if($link){
                     return view('panel/page_editlink', [
                         'menu' => 'links',
                         'page' => $page,
                         'link' => $link
                     ]);

                 }
             }
             return redirect('/panel');
        }
        public function editLinkAction($slug, $linkid, Request $request){
            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){
                $link = Link::where('id_page', $page->id)
                ->where('id', $linkid)
                ->first();

                if($link){
                    $fields = $request->validate([

                        'status' => ['required', 'boolean'],
                        'title' => ['required', 'min:2'],
                        'op_link_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:18048',
                        'href' => ['required', 'url'],
                        'op_bg_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                        'op_text_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                        'op_border_type' => ['required', Rule::in(['square', 'rounded'])]

                    ]);
                    if ($image = $request->file('op_link_image')) {
                        $destinationPath = 'media/uploads';
                        $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($destinationPath, $profileImage);
                        $input['op_link_image'] = "$profileImage";
                    }

                    $link->op_link_image = $input['op_link_image']?? '';
                    $link->status = $fields['status'];

                    $link->title = $fields['title'];
                    $link->href = $fields['href'];
                    $link->op_bg_color = $fields['op_bg_color'];
                    $link->op_text_color = $fields['op_text_color'];
                    $link->op_border_type = $fields['op_border_type'];
                    $link->save();
                    return redirect('/panel/'.$page->slug.'/links');

                }
            }
            return redirect('/panel');
        }
        public function delLink($slug, $linkid){
            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){
                $link = Link::where('id_page', $page->id)
                ->where('id', $linkid)
                ->first();

                if($link){
                    $link->delete();
                         //corrigindo as posições
                         $allLinks = Link::where('id_page', $page->id)
                         ->orderBy('order', 'ASC')
                         ->get();
                         foreach($allLinks as $linkKey => $linkItem ){
                             $linkItem->order = $linkKey;
                             $linkItem->save();
                         }
                    return redirect('/panel/'.$page->slug.'/links');
                }
            }
            return redirect('/panel');
        }

        public function pageDesign($slug){
            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){
                return view('panel/page_design', [
                    'menu' => 'design',
                    'page' => $page

                ]);
            }else{
                return redirect('/panel');
            }

        }
        //DESIGN ACTION

        public function pageDesignAction($slug, Request $request){


            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){

                $fields = $request->validate([


                    'op_title' => ['required', 'min:2'],

                    'op_profile_image' => 'image|mimes:png,gif,svg|max:12048',
                    'op_background_image' => 'image|mimes:jpg|max:12048',
                    'op_description' => ['required', 'min:2'],

                    'op_bg_value' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                    'op_font_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                    'op_fb_pixel' => ['min:0'],

                ]);
                if ($image = $request->file('op_profile_image')) {
                    $destinationPath = 'media/uploads';
                    $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $profileImage);
                    $input['op_profile_image'] = "$profileImage";
                }

                $slug = $page->slug;


                $page->id_user = $page->id_user;

                $page->op_title = $fields['op_title'];
                $page->op_profile_image = $input['op_profile_image']?? '';
                if ($image = $request->file('op_background_image')) {
                    $destinationPath = 'media/uploads';
                    $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $profileImage);
                    $input2['op_background_image'] = "$profileImage";
                }
                $page->op_background_image = $input2['op_background_image']?? '';

                $page->op_description = $fields['op_description'];
                $page->op_bg_value = $fields['op_bg_value'];
                $page->op_font_color = $fields['op_font_color'];

                $page->op_fb_pixel = $fields['op_fb_pixel']?? '';
                $page->save();


                return redirect('/panel/'.$page->slug.'/design');



            }else{
                return redirect('/panel');
            }
        }




        //FIM DESIGN ACTION

        public function pageStats($slug){
            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();

            if($page){
                $views = View::where('id_page', $page->id)->orderBy('id', 'desc')->get();
                return view('panel/page_stats', [
                    'menu' => 'stats',
                    'page' => $page,
                    'views' => $views
                ]);
            }else{
                return redirect('/panel');
            }

        }

        // IMPLEMENTAÇÃO DA FUNÇÃO DE ADCIONAR POSTS
        public function newPost($slug){
            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){
                return view('panel/page_editpost', [
                    'menu' => 'links',
                    'page' => $page

                ]);
            }else{
                return redirect('/panel');
            }
        }

        public function newPostAction($slug, Request $request){


            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){

                $fields = $request->validate([

                    'status' => ['required', 'boolean'],
                    'title' => ['required', 'min:2'],
                    'posttitle' => ['required', 'min:2'],
                    'op_post_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5948',

                    'href' => ['http://127.0.0.1:8000/'],
                    'op_bg_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                    'op_text_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                    'op_border_type' => ['required', Rule::in(['square', 'rounded'])]

                ]);
                if ($image = $request->file('op_post_image')) {
                    $destinationPath = 'media/uploads';
                    $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $profileImage);
                    $input['op_post_image'] = "$profileImage";
                }
                $totalLinks = Link::where('id_page', $page->id)->count();

                $newLink = new Link();
                $newLink->id_page = $page->id;
                $newLink->op_post_image = $input['op_post_image'];
                $newLink->status = $fields['status'];
                $newLink->order = $totalLinks;
                $newLink->posttitle = $fields['posttitle'];
                $newLink->title = $fields['title'];


                $newLink->href = $page->slug;
                $newLink->op_bg_color = $fields['op_bg_color'];
                $newLink->op_text_color = $fields['op_text_color'];
                $newLink->op_border_type = $fields['op_border_type'];
                $newLink->save();

                return redirect('/panel/'.$page->slug.'/links');



            }else{
                return redirect('/panel');
            }
        }

        public function editPost($slug, $linkid){
            $user = Auth::user();
             //proteção
             $page = Page::where('slug', $slug)
             ->where('id_user', $user->id)->first();
             if($page){
                 $link = Link::where('id_page', $page->id)
                 ->where('id', $linkid)
                 ->first();

                 if($link){
                     return view('panel/page_editpost', [
                         'menu' => 'links',
                         'page' => $page,
                         'link' => $link
                     ]);

                 }
             }
             return redirect('/panel');
        }
        public function editPostAction($slug, $linkid, Request $request){
            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){
                $link = Link::where('id_page', $page->id)
                ->where('id', $linkid)
                ->first();

                if($link){
                    $fields = $request->validate([

                        'status' => ['required', 'boolean'],
                        'posttitle' => ['required', 'min:2'],
                        'title' => ['required', 'min:2'],
                        'op_post_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:5948',
                        'href' => ['http://127.0.0.1:8000/'],
                        'op_bg_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                        'op_text_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                        'op_border_type' => ['required', Rule::in(['square', 'rounded'])]

                    ]);
                    if ($image = $request->file('op_post_image')) {
                        $destinationPath = 'media/uploads';
                        $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($destinationPath, $profileImage);
                        $input['op_post_image'] = "$profileImage";
                    }

                    $link->op_post_image = $input['op_post_image'];
                    $link->status = $fields['status'];

                    $link->posttitle = $fields['posttitle'];
                    $link->title = $fields['title'];
                    $link->href = $page->slug;
                    $link->op_bg_color = $fields['op_bg_color'];
                    $link->op_text_color = $fields['op_text_color'];
                    $link->op_border_type = $fields['op_border_type'];
                    $link->save();
                    return redirect('/panel/'.$page->slug.'/links');

                }
            }
            return redirect('/panel');
        }

        //FIM DA FUNÇÃO ADICIONAR POSTS

         // IMPLEMENTAÇÃO DA FUNÇÃO DE ADCIONAR POSTS VIDEO
         public function newPostVideo($slug){
            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){
                return view('panel/page_editpostvideo', [
                    'menu' => 'links',
                    'page' => $page

                ]);
            }else{
                return redirect('/panel');
            }
        }

        public function newPostVideoAction($slug, Request $request){


            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){

                $fields = $request->validate([

                    'status' => ['required', 'boolean'],
                    'title' => ['required', 'min:2'],
                    'posttitle' => ['required', 'min:2'],
                    'op_post_video' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm',

                    'href' => ['http://127.0.0.1:8000/'],
                    'op_bg_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                    'op_text_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                    'op_border_type' => ['required', Rule::in(['square', 'rounded'])]

                ]);
                if ($image = $request->file('op_post_video')) {
                    $destinationPath = 'media/uploads';
                    $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                    $image->move($destinationPath, $profileImage);
                    $input['op_post_video'] = "$profileImage";
                }
                $totalLinks = Link::where('id_page', $page->id)->count();

                $newLink = new Link();
                $newLink->id_page = $page->id;
                $newLink->op_post_video = $input['op_post_video'];
                $newLink->status = $fields['status'];
                $newLink->order = $totalLinks;
                $newLink->posttitle = $fields['posttitle'];
                $newLink->title = $fields['title'];


                $newLink->href = $page->slug;
                $newLink->op_bg_color = $fields['op_bg_color'];
                $newLink->op_text_color = $fields['op_text_color'];
                $newLink->op_border_type = $fields['op_border_type'];
                $newLink->save();

                return redirect('/panel/'.$page->slug.'/links');



            }else{
                return redirect('/panel');
            }
        }

        public function editPostVideo($slug, $linkid){
            $user = Auth::user();
             //proteção
             $page = Page::where('slug', $slug)
             ->where('id_user', $user->id)->first();
             if($page){
                 $link = Link::where('id_page', $page->id)
                 ->where('id', $linkid)
                 ->first();

                 if($link){
                     return view('panel/page_editpostvideo', [
                         'menu' => 'links',
                         'page' => $page,
                         'link' => $link
                     ]);

                 }
             }
             return redirect('/panel');
        }
        public function editPostVideoAction($slug, $linkid, Request $request){
            $user = Auth::user();
            //proteção
            $page = Page::where('slug', $slug)
            ->where('id_user', $user->id)->first();
            if($page){
                $link = Link::where('id_page', $page->id)
                ->where('id', $linkid)
                ->first();

                if($link){
                    $fields = $request->validate([

                        'status' => ['required', 'boolean'],
                        'posttitle' => ['required', 'min:2'],
                        'title' => ['required', 'min:2'],
                        'op_post_video' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
                        'href' => ['http://127.0.0.1:8000/'],
                        'op_bg_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                        'op_text_color' => ['required', 'regex:/^[#][0-9A-F]{3,6}$/i'],
                        'op_border_type' => ['required', Rule::in(['square', 'rounded'])]

                    ]);
                    if ($image = $request->file('op_post_video')) {
                        $destinationPath = 'media/uploads';
                        $profileImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($destinationPath, $profileImage);
                        $input['op_post_video'] = "$profileImage";
                    }

                    $link->op_post_video = $input['op_post_video'];
                    $link->status = $fields['status'];

                    $link->posttitle = $fields['posttitle'];
                    $link->title = $fields['title'];
                    $link->href = $page->slug;
                    $link->op_bg_color = $fields['op_bg_color'];
                    $link->op_text_color = $fields['op_text_color'];
                    $link->op_border_type = $fields['op_border_type'];
                    $link->save();
                    return redirect('/panel/'.$page->slug.'/links');

                }
            }
            return redirect('/panel');
        }


}
