<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ShowPosts extends Component
{
    use WithFileUploads, WithPagination;

    public $post, $image, $identificador;
    public $search = "";
    public $sort = "id";
    public $direction = "desc";
    public $open_edit = false;
    public $cantidad = '5';
    public $readyToLoad = false;

    protected $queryString = [
        "cantidad" => ['except' => '5'], 
        "sort" => ['except' => 'id'], 
        "direction" => ['except' => 'desc'], 
        "search" => ['except' => '']
    ];

    public function mount(){
        $this->identificador = rand();
        $this->post = new Post(); //instancia para guardar Post que luego en el modal open_edit de showpost se modificara
    }

    public function updatingSearch(){
        $this->resetPage();
    }

    protected $listeners = ['render', 'delete'];

    protected $rules = [
        'post.title' => 'required|min:3|max:60',
        'post.content' => 'required|max:192',
    ];
    
    public function order($value){
        if ($value == $this->sort) {
            if ($this->direction == 'desc') {
                $this->direction = 'asc';
            } else {
                $this->direction = 'desc';
            }
        } else {
            $this->sort = $value;
            $this->direction = 'asc';
        }
    }

    public function edit(Post $post){
        $this->post = $post;
        $this->open_edit = true;
    }

    public function update(){
        $this->validate();
        if ($this->image) {
            Storage::delete(['public/'.$this->post->image]);
            // $this->post->image = $this->image->store('public/posts');
            $this->post->image = $this->image->store('posts', 'public');
        }
        $this->post->save();
        $this->reset(['open_edit', 'image']);
        $this->identificador = rand();
        $this->emitTo('show-posts','render'); // Ya no es necesario emitir este evento, ya estamos en show-post
        $this->emit('alert', 'El post se actualizó sastisfactoriamente!');
    }

    public function loadPosts(){
        $this->readyToLoad = true;
    }

    public function delete(Post $post){
        $post->delete();
        Storage::delete(['public/'.$post->image]);
    }

    public function render(){

        if ($this->readyToLoad) {
            $posts = Post::where('title', 'like', '%'. $this->search . '%')
                ->orWhere('content', 'like', '%'. $this->search . '%')
                ->orderBy($this->sort, $this->direction)
                ->paginate($this->cantidad);
        }else {
            $posts = [];
        }

        return view('livewire.show-posts', compact('posts'));
    }
}
