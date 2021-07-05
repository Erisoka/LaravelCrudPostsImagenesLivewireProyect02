<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    use WithFileUploads;

    public $open = false;

    public $title, $content, $image, $identificador;

    public function mount(){
        $this->identificador = rand();
    }

    protected $rules = [
        'title' => 'required|min:3|max:60',
        'content' => 'required|max:192',
        'image' => 'image|max:5120',
    ];

    public function save(){
        $this->validate();

        $image = $this->image->store('public/posts');

        Post::create([
            'title' => $this->title,
            'content' => $this->content,
            'image' => $image,
        ]);

        $this->reset(['open', 'title', 'content', 'image']);
        $this->identificador = rand();
        $this->emitTo('show-posts','render');
        $this->emit('alert', 'El post se creó sastisfactoriamente!');
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
