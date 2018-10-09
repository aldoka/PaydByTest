FORMAT: 1A

# Sergey Kuzminich. PaydBy Test

# Podcasts [/api/podcasts]
Class PodcastController

## Display a list of Podcasts. [GET /api/podcasts/published]


## Store a newly created podcast in storage. [POST /api/podcasts]


+ Request (application/json)
    + Headers

            Accept: application/vnd.paydbytest.v1+json
    + Body

            {
                "name": "foo",
                "description": "bar",
                "feed_url": "http://test.gmail/test"
            }

+ Response 201 (application/json)
    + Body

            []

## Display the specified podcast. [GET /api/podcasts/{id}]


+ Request (application/json)
    + Headers

            Accept: application/vnd.paydbytest.v1+json
    + Body

            {
                "id": "1"
            }

+ Response 201 (application/json)
    + Body

            []

## Update the specified podcast in storage. [PUT /api/podcasts/{id}]


+ Request (application/json)
    + Headers

            Accept: application/vnd.paydbytest.v1+json
    + Body

            {
                "id": "1"
            }

+ Response 204 (application/json)

## Remove the specified podcast from storage. [DELETE /api/podcasts/{id}]


+ Request (application/json)
    + Headers

            Accept: application/vnd.paydbytest.v1+json
    + Body

            {
                "id": "1"
            }

+ Response 204 (application/json)

## Approves podcast and publish it. [GET /api/podcasts/approve/{id}]


+ Request (application/json)
    + Headers

            Accept: application/vnd.paydbytest.v1+json
    + Body

            {
                "id": "1"
            }

+ Response 201 (application/json)
    + Body

            []

# Comments [/api/comments]
Class CommentController

## Store a newly created comment in storage. [POST /api/comments/{podcastId}]


+ Request (application/json)
    + Headers

            Accept: application/vnd.paydbytest.v1+json
    + Body

            {
                "author_name": "foo",
                "author_email": "bar@foo.gmail",
                "comment": "blablabla"
            }

+ Response 201 (application/json)
    + Body

            []

## Remove the specified comment from storage. [DELETE /api/comments/{id}]


+ Request (application/json)
    + Headers

            Accept: application/vnd.paydbytest.v1+json
    + Body

            {
                "id": "1"
            }

+ Response 204 (application/json)