<?php

namespace Tests\Feature\WorkingExample;

use Tests\App\Services\DataLayer\Entities\PostEntity;
use Tests\TestCase;

class CrudTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testSuccessScenario()
    {
        $userId = 1;
        $data = [
            "user_id" => $userId,
            "unique_string_example" => "unique_string_example".rand(0,1000),
            "string_example" => "string_example",
            "int_example" =>  1,
            "enum_example" => "one",
        ];

        //create
        $response = $this->post('/api/WorkingExample/v1/posts', $data);
        $content = $response->getOriginalContent();
        $this->assertEquals(true, $content['success']);
        $createdEntity = $content['data'];
        $this->assertInstanceOf(PostEntity::class, $createdEntity);
        $this->assertEquals($data, array_intersect_key($data, (array) $createdEntity));

        //get
        $response = $this->get('/api/WorkingExample/v1/posts/'.$createdEntity->id);
        $content = $response->getOriginalContent();
        $this->assertEquals(true, $content['success']);
        /**
         * @var $entity PostEntity
         */
        $entity = $content['data'];
        $this->assertInstanceOf(PostEntity::class, $entity);
        $this->assertEquals($data, array_intersect_key($data, (array) $entity));
        $this->assertEquals($userId, $entity->user->id);

        //update
        $response = $this->post('/api/WorkingExample/v1/posts/'.$createdEntity->id, ['int_example' => 2]);
        $content = $response->getOriginalContent();
        $this->assertEquals(true, $content['success']);
        $entity = $content['data'];
        $this->assertInstanceOf(PostEntity::class, $entity);
        $this->assertEquals(2, $entity->int_example);

        //delete
        $response = $this->delete('/api/WorkingExample/v1/posts/'.$createdEntity->id);
        $content = $response->getOriginalContent();
        $this->assertEquals(true, $content['success']);

        //404
        $response = $this->get('/api/WorkingExample/v1/posts/'.$createdEntity->id);
        $content = $response->getOriginalContent();
        $this->assertEquals(false, $content['success']);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
