<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AdminUpdateUserTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Test Route View Admin Create User.
     *
     * @return void
     */
    public function testCreatesUser()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/users')
                ->click('table tbody tr:nth-child(1) td:nth-child(7) a:nth-child(2)')
                ->assertPathIs('/users/1')
                ->assertSee('Update USER PAGE');
        });
    }

    /**
     * Test Validation Admin Create User.
     *
     * @return void
     */
    public function testValidationUpdateUser()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/users/1/edit')
                ->press('Save Changes')
                ->assertPathIs('/users/create')
                ->assertSee('The password field is required.')
                ->assertSee('The birthday is not a valid date.')
                ->assertSee('The full name field is required.')
                ->assertSee('The email field is required.')
                ->assertSee('The address field is required.')
                ->assertSee('The phone number field is required.');
        });
    }

    /**
     * Test Admin create User success.
     *
     * @return void
     */
    public function testUpdateUserSuccess()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/users/create')
                ->type('password', '123123')
                ->type('full_name', 'test user')
                ->type('email', 'test.user@gmail.com')
                ->select('gender', 1)
                ->type('phone_number', '12345678')
                ->type('address', 'test address')
                ->script([
                    "document.querySelector('#date-picker').value = '2017-02-23'",
                ]);
            $browser->press('Create')
                ->assertPathIs('/users')
                ->assertSee('User Created!')
                ->screenShot('testCreateUserSuccess');
        });
        $this->assertDatabaseHas('users', ['email' => 'test.user@gmail.com']);
    }


    public function listCaseTestValidationForCreateUser()
    {
        return [
            ['', 'test user', 'test.user@gmail.com', '1', '12345678', 'test address', '2017-02-23', 'The password field is required.'],
            ['123456', '', 'test.user@gmail.com', '1', '12345678', 'test address', '2017-02-23', 'The full name field is required.'],
            ['123456', 'test user', '', '1', '12345678', 'test address', '2017-02-23', 'The email field is required.'],
            ['123456', 'test user', 'test.user@', '1', '12345678', 'test address', '2017-02-23', 'The email must be a valid email address.'],
            ['123456', 'test user', 'test.user@gmail.com', '1', '', 'test address', '2017-02-23', 'The phone number field is required.'],
            ['123456', 'test user', 'test.user@gmail.com', '1', 'abcdefgh', 'test address', '2017-02-23', 'The phone number must be a number.'],
            ['123456', 'test user', 'test.user@gmail.com', '1', '12345678', '', '2017-02-23', 'The address field is required.'],
            ['123456', 'test user', 'test.user@gmail.com', '1', '12345678', 'test address', '', 'The birthday is not a valid date.'],
        ];
    }

    /**
     * @dataProvider listCaseTestValidationForCreateUser
     *
     */
    public function testCreateUserFailValidation(
        $password,
        $full_name,
        $email,
        $gender,
        $phone_number,
        $address,
        $birthday,
        $expected
    )
    {

        $this->browse(function (Browser $browser) use (
            $password,
            $full_name,
            $email,
            $gender,
            $phone_number,
            $address,
            $birthday,
            $expected
        ) {

            $browser->loginAs(1)
                ->visit('/users/create')
                ->type('password', $password)
                ->type('full_name', $full_name)
                ->type('email', $email)
                ->select('gender', $gender)
                ->type('phone_number', $phone_number)
                ->type('address', $address)
                ->script([
                    "document.querySelector('#date-picker').value = '".$birthday."'",
                ]);
            $browser->press('Create')
                ->assertPathIs('/users/create');
        });
    }

    /**
     * Test Button Reset
     *
     */
    public function testBtnReset()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs(1)
                ->visit('/users/create')
                ->type('password', '123123')
                ->type('full_name', 'test user')
                ->type('email', 'test.user@gmail.com')
                ->type('phone_number', '12345678')
                ->type('address', 'test address')
                ->script([
                    "document.querySelector('#date-picker').value = '2017-02-23'",
                ]);
            $browser->press('Reset')
                ->assertPathIs('/users/create')
                ->assertInputValue('password', null)
                ->assertInputValue('full_name', null)
                ->assertInputValue('email', null)
                ->assertInputValue('birthday', null)
                ->assertInputValue('phone_number', null)
                ->assertInputValue('address', null)
                ->screenShot('testBtnReset');
        });
    }
}