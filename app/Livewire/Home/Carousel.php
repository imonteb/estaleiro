<?php

namespace App\Livewire\Home;

use Livewire\Component;

class Carousel extends Component
{
    public function render()
    {
        $carouselItems = [
            [
                'image' => asset('storage/images/home/img/carousel/1.jpg'),
                'title' => 'Welcome to Our Website',
                'description' => 'Image Home Carousel.',
            ],
            [
                'image' => asset('storage/images/home/img/carousel/2.jpg'),
                'title' => 'Welcome to Our Website',
                'description' => 'Image Home Carousel.',
            ],
            [
                'image' =>  asset('storage/images/home/img/carousel/3.jpg'),
                'title' => 'Welcome to Our Website',
                'description' => 'Image Home Carousel.',
            ],
            
            [
                'image' =>  asset('storage/images/home/img/carousel/4.jpg'),
                'title' => 'Welcome to Our Website',
                'description' => 'Image Home Carousel.',
            ],
            [
                'image' =>  asset('storage/images/home/img/carousel/5.jpg'),
                'title' => 'Welcome to Our Website',
                'description' => 'Image Home Carousel.',
            ],

        ];


        return view('livewire.home.carousel', compact('carouselItems'));
    }
}
