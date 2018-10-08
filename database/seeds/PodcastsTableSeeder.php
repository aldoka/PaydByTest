<?php

use App\Podcast;
use Illuminate\Database\Seeder;

class PodcastsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() : void
    {

        $now = new \DateTime();
        DB::table('podcasts')->insert(
            [
                'name' => 'The first simple published Podcast',
                'description'   => 'A simple description. It is published.',
                'marketing_url' => 'http://paydby.com/published-marketing',
                'url'    => 'http://paydby.com/published',
                'image'  => '/podcasts/dummy.jpg',
                'status' => Podcast::STATUS_PUBLISHED,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        DB::table('podcasts')->insert(
            [
                'name' => 'The second Podcast is under review',
                'description'   => 'It is under review.',
                'marketing_url' => 'http://paydby.com/review-marketing',
                'url'    => 'http://paydby.com/review',
                'image'  => '/podcasts/dummy2.jpg',
                'status' => Podcast::STATUS_REVIEW,
                'created_at' => $now,
                'updated_at' => $now,
            ]
        );

        factory(App\Podcast::class, 5)
            ->create()
            ->each(
                function (Podcast $bankpodcast) {
                    $comments = factory(\App\Comment::class, 5)->make();
                    $bankpodcast->comments()->saveMany($comments);
                }
            );
    }


}
