<?php

namespace App\Tests\Service;

use App\Services\OmdbApi;
use App\Services\OmdbApiRomain;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class OmdbApiRomainTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        $this->assertSame('test', $kernel->getEnvironment());

        $omdbApi = static::getContainer()->get(OmdbApiRomain::class);

        $infosOdmb = $omdbApi->fetch("Stranger Things");
        $posterExpected = "https://m.media-amazon.com/images/M/MV5BMDZkYmVhNjMtNWU4MC00MDQxLWE3MjYtZGMzZWI1ZjhlOWJmXkEyXkFqcGdeQXVyMTkxNjUyNQ@@._V1_SX300.jpg";

        $this->assertEquals($posterExpected, $infosOdmb['Poster']);

        $infosOdmb = $omdbApi->fetch("aaaaaaaa");
        $expected = "Movie not found!";
        $this->assertEquals($expected, $infosOdmb['Error']);
        

    }
}
