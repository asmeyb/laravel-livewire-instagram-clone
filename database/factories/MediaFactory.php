<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $url = $this->getUrl('post');
        $mime = $this->getMime($url);

        return [

            'url' => $url,
            'mime' => $mime,
            'mediable_id' => Post::factory(),
            'mediable_type' => function (array $attributes) {

                return Post::find($attributes['mediable_id'])->getMorphClass();
            }

        ];
    }


    function getUrl($type = 'post'): string
    {

        switch ($type) {
            case 'post':

                $urls = [

                    'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
                    'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
                    'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80',
                    'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800&q=80',
                    'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=800&q=80',
                    'https://images.unsplash.com/photo-1472214103451-9374bd1c798e?w=800&q=80',
                    'https://images.unsplash.com/photo-1433086966358-54859d0ed716?w=800&q=80',
                    'https://images.unsplash.com/photo-1426604966848-d7adac402bff?w=800&q=80'

                ];

                return $this->faker->randomElement($urls);
                break;

            case 'reel':

                $urls = [

                    'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4',
                    'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/SubaruOutbackOnStreetAndDirt.mp4',
                    'http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WeAreGoingOnBullrun.mp4'

                ];

                return $this->faker->randomElement($urls);
                break;

            default:
                # code...
                break;
        }
    }



    function getMime($url):string
    {

        #using current data only 

        if (str()->contains($url, 'gtv-videos-bucket')) {

            return 'video';
        } else if (str()->contains($url, 'images.unsplash.com')) {

            return 'image';
        }
    }

     #chainable methods
    function reel() : Factory {
        $url = $this->getUrl('reel');
        $mime = $this->getMime($url);

        return $this->state(function(array $attributes) use($url,$mime) {

            return [
                'mime'=>$mime,
                'url'=>$url,

            ];

        });

        
    }


     #chainable methods
     function post() : Factory {
        $url = $this->getUrl('post');
        $mime = $this->getMime($url);

        return $this->state(function(array $attributes) use($url,$mime) {

            return [
                'mime'=>$mime,
                'url'=>$url,

            ];

        });

        
    }



}