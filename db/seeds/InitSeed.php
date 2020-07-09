<?php


use Phinx\Seed\AbstractSeed;

class InitSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run()
    {
        $this->table('roles')->insert(
            [
                [
                    'id' => 1,
                    'role_name' => 'administrator',
                ],
                [
                    'id' => 2,
                    'role_name' => 'moderator',
                ],
                [
                    'id' => 3,
                    'role_name' => 'korisnik',
                ]
            ]
        )->save();

        $this->table('users')->insert(
            [
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => password_hash('admin', PASSWORD_DEFAULT),
                'name' => null,
                'lastname' => null,
                'phone_number' => null,
                'avatar_name' => 'noavatar.png',
                'roles_id' => 1
            ]
        )->save();

        $this->table('users')->insert(
            [
                'username' => 'moderator',
                'email' => 'moderator@gmail.com',
                'password' => password_hash('moderator', PASSWORD_DEFAULT),
                'name' => null,
                'lastname' => null,
                'phone_number' => null,
                'avatar_name' => 'noavatar.png',
                'roles_id' => 2
            ]
        )->save();

        $this->table('users')->insert(
            [
                'username' => 'korisnik',
                'email' => 'korisnik@gmail.com',
                'password' => password_hash('korisnik', PASSWORD_DEFAULT),
                'name' => 'korisnik',
                'lastname' => 'korisnik',
                'phone_number' => null,
                'avatar_name' => 'noavatar.png',
                'roles_id' => 3
            ]
        )->save();

        $this->table('informations')->insert(
            [
                'informations' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
            ]
        )->save();

        $this->table('contact_info')->insert(
            [
                'name' => 'Rent a car Speed',
                'email' => 'info@rentacarspeed.rs',
                'mobile_number' => '+381/(0)63-19-856',
                'fix_number' => '+381 /(0)35-8100-580',
                'adress' => 'Vožda Karađorđa 65, Paraćin',
            ]
        )->save();

        $this->table('ratings')->insert(
            [
                [
                    'name' => 'Pera Perić',
                    'rating' => 5,
                    'comment' => 'Ova agencija je najbolja!',
                    'allowed' => 1,
                    'deleted' => 0,
                ],
                [
                    'name' => 'Ana Sokolović',
                    'rating' => 4,
                    'comment' => 'Može i bolje!',
                    'allowed' => 1,
                    'deleted' => 0,
                ],
                [
                    'name' => 'Boban Bobanović',
                    'rating' => 5,
                    'comment' => 'Usluga na nivou!',
                    'allowed' => 1,
                    'deleted' => 0,
                ],
            ]
        )->save();
    }
}
