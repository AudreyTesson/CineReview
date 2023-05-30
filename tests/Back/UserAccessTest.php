<?php

namespace App\Tests\Back;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserAccessTest extends WebTestCase
{
    /**
     * @dataProvider getUrls
     * 
     * @param string $url
     */
    public function testBack($url, $email, $codeStatus): void
    {
        $client = self::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail($email);
        $client->loginUser($testUser);

        $client->request('GET', $url);

        $this->assertResponseStatusCodeSame($codeStatus);
    }


    /**
     * fournit TOUT les paramètres pour une function
     * 
     * @return array
     */
    public function getUrls()
    {
        yield ['/back/main', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/movie', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/casting/new', 'user@user.com', Response::HTTP_FORBIDDEN];
        yield ['/back/casting/new', 'manager@manager.com', Response::HTTP_FORBIDDEN];
        yield ['/back/casting/new', 'admin@admin.com', Response::HTTP_OK];
    }
}
