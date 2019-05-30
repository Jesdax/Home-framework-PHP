<?php
namespace Tests\Framework;

use Framework\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{

    private function makeValidator(array $params)
    {
        return new Validator($params);
    }


    public function testRequiredIfFail()
    {
        $errors = $this->makeValidator(['name' => 'joe'])
        ->required('name', 'content')
        ->getErrors();
        $this->assertCount(1, $errors);

        //Test if string is ok
        // $this->assertEquals("Le champs content est requis", (string) $errors['content']);
    }

    public function testNotEmpty()
    {
        $errors = $this->makeValidator(['name' => 'joe', 'content' => ''])
        ->notEmpty('content')
        ->getErrors();
        $this->assertCount(1, $errors);

        //Test if string is ok
        // $this->assertEquals("Le champs content est requis", (string) $errors['content']);
    }

    public function testRequiredIfSuccess()
    {
        $errors = $this->makeValidator(['name' => 'joe', 'content' => 'content'])
        ->required('name', 'content')
        ->getErrors();
        $this->assertCount(0, $errors);
    }


    public function testSlugSuccess()
    {
        $errors = $this->makeValidator(['slug' => 'aze-aze1'])
            ->slug('slug')
            ->getErrors();
        $this->assertCount(0, $errors);
    }

    public function testSlugError()
    {
        $errors = $this->makeValidator([
            'slug' => 'aze-Aze12',
            'slug2' => 'aze-Aze_az22',
            'slug3' => 'aze--aAze-aze'])
            ->slug('slug')
            ->slug('slug2')
            ->slug('slug3')
            ->slug('slug4')
            ->getErrors();
        $this->assertCount(3, $errors);
    }


    public function testLength()
    {
        $params = ['slug' => '123456789'];
        $this->assertCount(0, $this->makeValidator($params)->length('slug', 3)->getErrors());
        $errors = $this->makeValidator($params)->length('slug', 12)->getErrors();
        $this->assertCount(1, $errors);
        $this->assertEquals('Le champs slug doit contenir plus de 12 caractères', (string)$errors['slug']);
        $this->assertCount(1, $this->makeValidator($params)->length('slug', 3, 4)->getErrors());
        $this->assertCount(0, $this->makeValidator($params)->length('slug', 3, 20)->getErrors());
        $this->assertCount(0, $this->makeValidator($params)->length('slug', null, 20)->getErrors());
        $this->assertCount(1, $this->makeValidator($params)->length('slug', null, 8)->getErrors());
    }

    public function testDateTime()
    {
        $params = ['date' => '2012-12-12 11:12:13'];
        $this->assertCount(0, $this->makeValidator($params)->dateTime('date')->getErrors());
        $this->assertCount(0, $this->makeValidator(['date' => '2012-12-12 00:00:00'])->dateTime('date')->getErrors());
        $this->assertCount(1, $this->makeValidator(['date' => '2012-21-12'])->dateTime('date')->getErrors());
        $this->assertCount(1, $this->makeValidator(['date' => '2013-02-29 11:12:13'])->dateTime('date')->getErrors());
    }



}