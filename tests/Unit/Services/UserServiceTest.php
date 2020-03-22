<?php
/**
 * User: mlawson
 * Date: 2018-11-27
 * Time: 12:52
 */

namespace Tests\Unit\Services;


use NeubusSrm\Models\Auth\User;
use NeubusSrm\Models\Org\Company;
use NeubusSrm\Services\UserService;
use Tests\TestCase;

const USER_PASSWORD = 'password';

/**
 * Class UserServiceTest
 * @package Tests\Unit\Services
 */
class UserServiceTest extends TestCase
{

	/**
	 * @var UserService
	 */
	protected $userService;

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * UserServiceTest constructor.
	 * @param UserService $userService
	 */

	public function setUp() {
		parent::setUp(); // TODO: Change the autogenerated stub
		$this->user = factory(User::class)->create(['password' => bcrypt(USER_PASSWORD)]);
		$this->userService = resolve(UserService::class);
        $this->userService->loginUser($this->user->email, USER_PASSWORD);
	}

	public function tearDown() {
		unset($this->user);
		parent::tearDown(); // TODO: Change the autogenerated stub
	}


	/**
	 * @covers \NeubusSrm\Services\UserService::logoutUser
	 */
	public function testLogoutUser() {

		$this->actingAs($this->user);
		self::assertTrue(\Auth::check());
		$this->userService->logoutUser();
		self::assertFalse(\Auth::check());
	}

    /**
     * @dataProvider usersSearchProvider
     * @param string $sortBy
     * @param string $order
     * @param string $keyword
     * @param array $expected
     * @param int $total
     */
    public function testUsersSearch(string $sortBy, string $order, string $keyword, array $expected, int $total) : void {
        $users = $this->userService->userSearch($sortBy, $order, $keyword);
        $this->assertEquals($total, $users['total']);
        foreach($users['result'] as $key => $user){
            $this->assertEquals($user['name'], $expected[$key]['name']);
            $this->assertEquals($user['email'], $expected[$key]['email']);
            $this->assertEquals($user['company_name'], $expected[$key]['company_name']);
        }
    }

    /**
     * @return array
     */
    public function usersSearchProvider() : array {
        $this->refreshApplication();
        $company1 = factory(Company::class)->create(['company_name' => 'aaa']);
        $company2 = factory(Company::class)->create(['company_name' => 'bbb']);
        $company3 = factory(Company::class)->create(['company_name' => 'ccc']);
        $company4 = factory(Company::class)->create(['company_name' => 'ddd']);
        $user1 = factory(User::class)->create(['name' => 'namesearch', 'company_id' => $company1->id, 'email' => '1@neuone.com']);
        $user2 = factory(User::class)->create(['name' => 'namesearch', 'company_id' => $company2->id, 'email' => '2@neuone.com']);
        $user3 = factory(User::class)->create(['name' => 'namesearch', 'company_id' => $company3->id, 'email' => '3@neuone.com']);
        $user4 = factory(User::class)->create(['name' => 'nonefound', 'company_id' => $company4->id, 'email' => '4@neuone.com']);
        return [['company_name', 'asc', 'namesearc', [['email'=>'1@neuone.com', 'company_name'=>'aaa', 'name'=>'namesearch'],
            ['email'=>'2@neuone.com', 'company_name'=>'bbb', 'name'=>'namesearch'],
            ['email'=>'3@neuone.com', 'company_name'=>'ccc', 'name'=>'namesearch']], 3]];
    }

}
