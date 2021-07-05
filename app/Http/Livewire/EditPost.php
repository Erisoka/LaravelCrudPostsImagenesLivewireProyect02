<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class EditPost extends Component
{
    use WithFileUploads;

    public $post, $image, $identificador;
    public $open = false;

    public function mount(Post $post){
        $this->post = $post;
        $this->identificador = rand();
    }

    protected $rules = [
        'post.title' => 'required|min:3|max:60',
        'post.content' => 'required|max:192',
        'image' => 'nullable|image|max:5120',
    ];

    public function save(){
        $this->validate();
        if ($this->image) {
            Storage::delete([$this->post->image]);
            // $this->post->image = $this->image->store('public/posts');
            $this->post->image = $this->image->store('posts', 'public');
        }
        $this->post->save();
        $this->reset(['open', 'image']);
        $this->identificador = rand();
        $this->emitTo('show-posts','render'); 
        $this->emit('alert', 'El post se actualizó sastisfactoriamente!');
    }

    public function render()
    {
        return view('livewire.edit-post');
    }
}
