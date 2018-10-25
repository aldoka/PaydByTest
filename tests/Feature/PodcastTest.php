<?php

namespace Tests\Unit;

use App\Podcast;
use Dingo\Api\Http\Response;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PodcastTest extends TestCase
{

    const GET_INDEX = self::BASE_URI . '/podcasts/published';
    const GET_SHOW = self::BASE_URI . '/podcasts';
    const POST_ITEM = self::BASE_URI . '/podcasts';
    const PUT_ITEM = self::BASE_URI . '/podcasts';
    const DELETE_ITEM = self::BASE_URI . '/podcasts';

    const CORRECT_HEADERS = ['Accept' => 'application/vnd.podcast.v1+json'];

    const INCORRECT_HEADERS = [
        'no headers' => [[]],
        'accept application/json' => [['Accept' => 'application/json']],
    ];

    const CORRECT_IMAGE = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=';


    /**
     * Generates a correct podcast with the correct image
     *
     * @param array $predefinedValues
     * @return array
     */
    private function _generateCorrectPodcast(array $predefinedValues = array()): array
    {
        /** @var \App\Podcast $correctPodcast */
        $correctPodcast = factory(\App\Podcast::class)->make($predefinedValues);
        $correctPodcast->image = self::CORRECT_IMAGE;

        return $correctPodcast->toArray();
    }


    /**
     * Data provider
     *
     * @return array
     */
    public function incorrectHeaders(): array
    {
        return self::INCORRECT_HEADERS;
    }


    /**
     * Data provider
     *
     * @return array
     */
    public function incorrectPodcasts(): array
    {
        $result = [
            'no name' => [$this->_generateCorrectPodcast(['name' => null])],
            'no feed url' => [$this->_generateCorrectPodcast(['feed_url' => null])],
            'no description ' => [$this->_generateCorrectPodcast(['description' => null])],

        ];

        return $result;
    }


    /**
     * Data provider
     *
     * @return array
     */
    public function publishedPodcastId() : array
    {
        $publishedPodcast = factory(\App\Podcast::class)->state('published')->create();

        return [
            'published podcast' => [$publishedPodcast->id],
        ];
    }


    /**
     * Data provider
     *
     * @return array
     */
    public function reviewPodcastId(): array
    {
        $reviewPodcast = factory(\App\Podcast::class)->state('review')->create();

        return [
            'review podcast' => [$reviewPodcast->id],
        ];
    }


    public function testGetIndexSuccess(): void
    {
        $response = $this->getJson(self::GET_INDEX, self::CORRECT_HEADERS);

        $response->assertOk();
    }


    /**
     * @dataProvider incorrectHeaders
     *
     * @param array $incorrectHeaders
     */
    public function testGetIndexBadRequest(array $incorrectHeaders): void
    {
        $response = $this->getJson(self::GET_INDEX, [], $incorrectHeaders);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(['message' => "Accept header could not be properly parsed because of a strict matching process."]);
    }


    public function testPostStoreSuccess(): void
    {
        $podcast = $this->_generateCorrectPodcast();

        $response = $this->postJson(self::POST_ITEM, $podcast, self::CORRECT_HEADERS);

        $response->assertStatus(Response::HTTP_CREATED);
    }


    /**
     * @dataProvider incorrectPodcasts
     *
     * @param array $podcast
     */
    public function testPostStoreUnprocessableEntity(array $podcast): void
    {
        $response = $this->postJson(self::POST_ITEM, $podcast, self::CORRECT_HEADERS);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function testGetShowSuccess(): void
    {
        /** @var Podcast $existingPodcast */
        $existingPodcast = factory(\App\Podcast::class)->state('published')->create();

        $response = $this->getJson(self::GET_SHOW . '/' . $existingPodcast->id, self::CORRECT_HEADERS);

        $response->assertOk();
    }


    public function testGetShowNotFound(): void
    {
        /** @var Podcast $existingPodcast */
        $existingPodcast = factory(\App\Podcast::class)->state('published')->create();

        $response = $this->getJson(self::GET_SHOW . '/' . ($existingPodcast->id + 1000), self::CORRECT_HEADERS);

        $response->assertNotFound();
    }


    public function testPutUpdateSuccess(): void
    {
        $podcast = $this->_generateCorrectPodcast();

        /** @var Podcast $existingPodcast */
        $existingPodcast = factory(\App\Podcast::class)->state('published')->create();

        $response = $this->putJson(self::PUT_ITEM . '/' . $existingPodcast->id, $podcast, self::CORRECT_HEADERS);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }


    /**
     * @dataProvider incorrectPodcasts
     * @param array $podcast
     */
    public function testPutUpdateUnprocessableEntity(array $podcast): void
    {
        /** @var Podcast $existingPodcast */
        $existingPodcast = factory(\App\Podcast::class)->state('published')->create();

        $response = $this->putJson(self::PUT_ITEM . '/' . $existingPodcast->id, $podcast, self::CORRECT_HEADERS);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function testPutUpdateNotFound(): void
    {
        $podcast = $this->_generateCorrectPodcast();

        /** @var Podcast $existingPodcast */
        $existingPodcast = factory(\App\Podcast::class)->state('published')->create();

        $response = $this->putJson(self::PUT_ITEM . '/' . ((int)$existingPodcast->id + 1000), $podcast, self::CORRECT_HEADERS);

        $response->assertNotFound();
    }


    /**
     * @dataProvider publishedPodcastId
     * @dataProvider reviewPodcastId
     */
    public function testDeleteDestroySuccess($podcastId): void
    {
        $response = $this->deleteJson(self::DELETE_ITEM . '/' . $podcastId, [], self::CORRECT_HEADERS);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }


    public function testDeleteDestroyNotFound(): void
    {
        /** @var Podcast $existingPodcast */
        $existingPodcast = factory(\App\Podcast::class)->state('published')->create();
        $responseNotExists = $this->deleteJson(self::DELETE_ITEM . '/' . ($existingPodcast->id + 1000), [], self::CORRECT_HEADERS);

        $responseNotExists->assertNotFound();
    }


}