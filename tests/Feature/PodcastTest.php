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

    use RefreshDatabase;

    const GET_INDEX = self::BASE_URI . '/podcasts/published';
    const GET_SHOW  = self::BASE_URI . '/podcasts';
    const POST_ITEM = self::BASE_URI . '/podcasts';
    const PUT_ITEM  = self::BASE_URI . '/podcasts';

    const CORRECT_HEADERS = [
        'accept vnd.podcast.v1' => [['Accept' => 'application/vnd.podcast.v1+json']],
    ];

    const INCORRECT_HEADERS = [
        'no headers' => [[]],
        'accept application/json' => [['Accept' => 'application/json']],
    ];

    const CORRECT_IMAGE = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEUAAACnej3aAAAAAXRSTlMAQObYZgAAAApJREFUCNdjYAAAAAIAAeIhvDMAAAAASUVORK5CYII=';


    /**
     * Generates a correct podcast with the correct image and published status
     *
     * @param bool $asArray
     * @return Podcast|array
     */
    private function _generateCorrectPodcast(bool $asArray = true)
    {
        /** @var \App\Podcast $correctPodcast */
        $correctPodcast = factory(\App\Podcast::class)->make();
        $correctPodcast->image = self::CORRECT_IMAGE;
        $correctPodcast->status = Podcast::STATUS_PUBLISHED;

        return ($asArray) ? $correctPodcast->toArray() : $correctPodcast;
    }


    /**
     * @return array
     */
    public function correctHeaders() : array
    {
        return self::CORRECT_HEADERS;
    }


    /**
     * @return array
     */
    public function incorrectHeaders() : array
    {
        return self::INCORRECT_HEADERS;
    }


    /**
     * @return array
     */
    public function incorrectPodcastsCorrectHeaders() : array
    {
        $result = [];
        foreach (self::CORRECT_HEADERS as $explanation => $headers) {
            $noNamePodcast = $this->_generateCorrectPodcast();
            $noNamePodcast['name'] = null;
            $result ['no name ' . $explanation]= [$noNamePodcast, current($headers)];

            $noFeedUrlPodcast = $this->_generateCorrectPodcast();
            $noFeedUrlPodcast['feed_url'] = null;
            $result ['no feed url ' . $explanation]= [$noFeedUrlPodcast, current($headers)];

            $noDescriptionPodcast = $this->_generateCorrectPodcast();
            $noDescriptionPodcast['description'] = null;
            $result ['no description ' . $explanation]= [$noDescriptionPodcast, current($headers)];
        }

        return $result;
    }


    /**
     * @dataProvider correctHeaders
     *
     * @param array $correctHeaders
     */
    public function testGetIndexSuccess(array $correctHeaders) : void
    {
        $response = $this->getJson(self::GET_INDEX, $correctHeaders);

        $response->assertOk();
    }


    /**
     * @dataProvider incorrectHeaders
     *
     * @param array $incorrectHeaders
     */
    public function testGetIndexBadRequest(array $incorrectHeaders) : void
    {
        $response = $this->getJson(self::GET_INDEX, [], $incorrectHeaders);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(['message' => "Accept header could not be properly parsed because of a strict matching process."]);
    }


    public function testPostStoreSuccess() : void
    {
        $podcast = $this->_generateCorrectPodcast();
        $headers = current(current(self::CORRECT_HEADERS));

        $response = $this->postJson(self::POST_ITEM, $podcast, $headers);

        $response->assertStatus(Response::HTTP_CREATED);
    }


    public function testPostStoreBadRequest() : void
    {
        $podcast = $this->_generateCorrectPodcast();
        $headers = current(current(self::CORRECT_HEADERS));

        $response = $this->getJson(self::POST_ITEM, $podcast, $headers);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertJson(['message' => "Accept header could not be properly parsed because of a strict matching process."]);
    }


    /**
     * @dataProvider incorrectPodcastsCorrectHeaders
     *
     * @param array $podcast
     * @param array $headers
     */
    public function testPostStoreUnprocessableEntity(array $podcast, array $headers) : void
    {
        $response = $this->postJson(self::POST_ITEM, $podcast, $headers);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    /**
     * @dataProvider correctHeaders
     */
    public function testGetShowSuccess(array $headers) : void
    {
        /** @var Podcast $existingPodcast */
        $existingPodcast = $this->_generateCorrectPodcast(false);
        $existingPodcast->save();

        $response = $this->getJson(self::GET_SHOW . '/' . $existingPodcast->id, $headers);

        $response->assertOk();
    }


    /**
     * @dataProvider incorrectHeaders
     */
    public function testGetShowBadRequest(array $headers) : void
    {
        /** @var Podcast $existingPodcast */
        $existingPodcast = $this->_generateCorrectPodcast(false);
        $existingPodcast->save();

        $response = $this->getJson(self::GET_SHOW . '/' . $existingPodcast->id, $headers);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }


    /**
     * @dataProvider correctHeaders
     */
    public function testGetShowNotFound(array $headers) : void
    {
        /** @var Podcast $existingPodcast */
        $existingPodcast = $this->_generateCorrectPodcast(false);
        $existingPodcast->save();

        $response = $this->getJson(self::GET_SHOW . '/' . ($existingPodcast->id + 100), $headers);

        $response->assertNotFound();
    }


    public function testPutUpdateSuccess() : void
    {
        $podcast = $this->_generateCorrectPodcast();
        $headers = current(current(self::CORRECT_HEADERS));

        /** @var Podcast $existingPodcast */
        $existingPodcast = $this->_generateCorrectPodcast(false);
        $existingPodcast->save();

        $response = $this->putJson(self::PUT_ITEM . '/' . $existingPodcast->id, $podcast, $headers);

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }


    /**
     * @dataProvider incorrectPodcastsCorrectHeaders
     */
    public function testPutUpdateUnprocessableEntity(array $podcast, array $headers) : void
    {
        /** @var Podcast $existingPodcast */
        $existingPodcast = $this->_generateCorrectPodcast(false);
        $existingPodcast->save();

        $response = $this->putJson(self::PUT_ITEM . '/' . $existingPodcast->id, $podcast, $headers);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function testPutUpdateNotFound() : void
    {
        $podcast = $this->_generateCorrectPodcast();
        $headers = current(current(self::CORRECT_HEADERS));

        /** @var Podcast $existingPodcast */
        $existingPodcast = $this->_generateCorrectPodcast(false);
        $existingPodcast->save();

        $response = $this->putJson(self::PUT_ITEM . '/' . ((int)$existingPodcast->id + 1000), $podcast, $headers);

        $response->assertNotFound();
    }


}