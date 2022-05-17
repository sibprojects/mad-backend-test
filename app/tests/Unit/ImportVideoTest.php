<?php

namespace App\Tests;

use App\ImportVideo\ImportVideo;
use App\ImportVideo\ImportVideoFlub;
use App\ImportVideo\ImportVideoGlorf;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class ImportVideoTest extends TestCase
{

    public function testVideoFlubClass(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);

        $flub = new ImportVideoFlub($em);
        $flub->rows = '--- 
- 
  labels: cats, cute, funny
  name: "funny cats"
  url: "http://glorf.com/videos/asfds.com"
';

        // test read
        $flub->prepare();
        $this->assertCount(1, $flub->rows, 'Error Flub preparing test 1!');
        $this->assertCount(3, $flub->rows[0], 'Error Flub preparing test 2!');

        // test parse
        $flub->parse();
        $this->assertEquals([
            [
                'name' => 'funny cats',
                'url' => 'http://glorf.com/videos/asfds.com',
                'tags' => 'cats,cute,funny',
            ]
        ], $flub->rows, 'Error Flub parsing text');

        $this->assertTrue($flub->save(), 'Save Flub error!');
    }

    public function testVideoGlorfClass(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);

        $glorf = new ImportVideoGlorf($em);
        $glorf->rows = '{"videos": [
            {"tags": ["microwave","cats","peanutbutter"],
            "url": "http://glorf.com/videos/3",
            "title": "science experiment goes wrong"
        }]}';

        // test read
        $glorf->prepare();
        $this->assertCount(1, $glorf->rows, 'Error Glorf preparing test 1!');
        $this->assertCount(1, $glorf->rows['videos'], 'Error Glorf preparing test 2!');
        $this->assertCount(3, $glorf->rows['videos'][0], 'Error Glorf preparing test 2!');

        // test parse
        $glorf->parse();
        $this->assertEquals([
            [
                'name' => 'science experiment goes wrong',
                'url' => 'http://glorf.com/videos/3',
                'tags' => 'microwave,cats,peanutbutter',
            ]
        ], $glorf->rows, 'Error Glorf parsing text');

        $this->assertTrue($glorf->save(), 'Save Glorf error!');
    }

}
