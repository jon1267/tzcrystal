<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Settings;
use App\Entity\Products;
use App\Utils\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * для Минта ?(.)bin/console doctrine:fixtures:load  --purge-with-truncate
     * для Выни php bin/console doctrine:fixtures:load  --purge-with-truncate
     */

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadSettings($manager);
        $this->loadProducts($manager);
        $this->loadUsers($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        $fullNames = ['John Doe', 'David Lee', 'Jek Dove'];
        $emails = ['test1@test.com', 'test2@test.com', 'test3@test.com',];

        $data = [];
        for ($i = 0; $i < sizeof($emails); $i++) {
            $data[] = [
                'fullname' => $name = $fullNames[$i],
                'username' => Slugger::str_slug($name, '_'),
                'email' => $emails[$i],
                'password' => '123456'
            ];
        }

        foreach ($data as $item) {
            $user = new User();

            $user->setFullname($item['fullname']);
            $user->setUsername($item['username']);
            $user->setEmail($item['email']);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $item['password']));

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function loadSettings(ObjectManager $manager)
    {
        $data = [
            'general' => [
                [
                    'key' => 'logo',
                    'label' => 'Логотип',
                    'type' => 'image',
                ],
                [
                    'key' => 'favicon',
                    'label' => 'Иконка сайта (фавикон)',
                    'type' => 'image',
                ],
                [
                    'key' => 'tag_title',
                    'label' => 'Тег title',
                    'type' => 'text',
                    'value' => 'Mini Shop ECommerce'
                ],
                [
                    'key' => 'meta_tag_description',
                    'label' => 'Мета-тег description',
                    'type' => 'text',
                ],
                [
                    'key' => 'meta_tag_keywords',
                    'label' => 'Мета-тег keywords',
                    'type' => 'text',
                ],
                [
                    'key' => 'top_delivery',
                    'label' => 'Срок доставки в шапке',
                    'value' => '3-5 business days delivery & free returns',
                    'type' => 'text',
                ],
            ],
            'company' => [
                [
                    'key' => 'top_phone',
                    'label' => 'Телефон в шапке',
                    'value' => '+ 1235 2355 98',
                    'type' => 'text',
                ],
                [
                    'key' => 'footer_phone',
                    'label' => 'Телефон в футере',
                    'value' => '+2 392 3929 210',
                    'type' => 'text',
                ],
                [
                    'key' => 'top_email',
                    'label' => 'Email в шапке',
                    'value' => 'youremail@email.com',
                    'type' => 'text',
                ],
                [
                    'key' => 'footer_email',
                    'label' => 'Email в футере',
                    'value' => 'info@yourdomain.com',
                    'type' => 'text',
                ],
                [
                    'key' => 'footer_address',
                    'label' => 'Адрес в футере',
                    'value' => '203 Fake St. Mountain View, San Francisco, California, USA',
                    'type' => 'text',
                ],
            ],
        ];

        foreach ($data as $group => $items ) {
            foreach ($items as $item) {

                $settings = new Settings();

                $item['group'] = $group;
                $item['value'] = $item['value'] ?? '';

                $settings->setKeyb($item['key']);
                $settings->setLabel($item['label']);
                $settings->setValue($item['value']);
                $settings->setType($item['type']);
                $settings->setGroups($item['group']);
                $settings->setTime(new \DateTime());

                $manager->persist($settings);
            }
        }

        $manager->flush();
    }

    private function loadProducts(ObjectManager $manager)
    {
        $manufacts = ['Nike ','Nike ',  'Puma ', 'Reebok '];
        $randWords = ['alia ','lui ','qui ','ubex ','molest ','diox ','sit ','elect '];

        //$manufacts = ['Найк ','Найк ',  'Пума ', 'Рибок '];
        //$randWords = ['спорт ','актив ','лагуна ','парадайс ','трибека ','эверест ','патриот ','электра '];


        $data = [];
        for ($i = 1; $i < sizeof($randWords)+1; $i++) {
            $data[] =
                [
                    // тут mb_convert_case() делает 1-ую буку слова заглавной для русских слов ( чего ucfirst() не умеет... )
                    //'name' => $name = $manufacts[random_int(0,3)] . mb_convert_case($randWords[rand(0,7)], MB_CASE_TITLE, "UTF-8") . '2019 iD' . rand(1,50),
                    'name' => $name = $manufacts[random_int(0,3)] . ucfirst($randWords[rand(0,7)]) . '2019 iD' . rand(1,50),
                    'slug' => Slugger::str_slug($name),
                    'description' => $this->getRandomText(255),
                    'price' => random_int(11, 14)*10,
                    'img' => 'minishop/images/product-' . $i .'.png',
                ];
        }

        foreach ($data as $item) {

            $products = new Products();

            $products->setName($item['name']);
            $products->setSlug($item['slug']);
            $products->setDescription($item['description']);
            $products->setPrice($item['price']);
            $products->setImg($item['img']);

            $manager->persist($products);
        }

        $manager->flush();

    }


    private function getPhrases(): array
    {
        return [
            'Lorem ipsum dolor sit amet consectetur adipiscing elit',
            'Pellentesque vitae velit ex',
            'Mauris dapibus risus quis suscipit vulputate',
            'Eros diam egestas libero eu vulputate risus',
            'In hac habitasse platea dictumst',
            'Morbi tempus commodo mattis',
            'Ut suscipit posuere justo at vulputate',
            'Ut eleifend mauris et risus ultrices egestas',
            'Aliquam sodales odio id eleifend tristique',
            'Urna nisl sollicitudin id varius orci quam id turpis',
            'Nulla porta lobortis ligula vel egestas',
            'Curabitur aliquam euismod dolor non ornare',
            'Sed varius a risus eget aliquam',
            'Nunc viverra elit ac laoreet suscipit',
            'Pellentesque et sapien pulvinar consectetur',
            'Ubi est barbatus nix',
            'Abnobas sunt hilotaes de placidus vita',
            'Ubi est audax amicitia',
            'Eposs sunt solems de superbus fortis',
            'Vae humani generis',
            'Diatrias tolerare tanquam noster caesium',
            'Teres talis saepe tractare de camerarius flavum sensorem',
            'Silva de secundus galatae demitto quadra',
            'Sunt accentores vitare salvus flavum parses',
            'Potus sensim ad ferox abnoba',
            'Sunt seculaes transferre talis camerarius fluctuies',
            'Era brevis ratione est',
            'Sunt torquises imitari velox mirabilis medicinaes',
            'Mineralis persuadere omnes finises desiderium',
            'Bassus fatalis classiss virtualiter transferre de flavum',
        ];
    }

    private function getRandomText(int $maxLength = 255): string
    {
        $phrases = $this->getPhrases();
        shuffle($phrases);

        while (mb_strlen($text = implode('. ', $phrases).'.') > $maxLength) {
            array_pop($phrases);
        }

        return $text;
    }

}
