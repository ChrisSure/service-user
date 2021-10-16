<?php

namespace App\Tests\Unit\Service\User;

use App\Entity\User\Permission;
use App\Repository\User\PermissionRepository;
use App\Service\Auth\PasswordHashService;
use App\Service\Helper\SerializeService;
use App\Service\User\PermissionService;
use App\Tests\Unit\Base;
use Mockery;

class PermissionServiceTest extends Base
{
    private $permissionMock;

    private $permissionRepositoryMock;

    private $serializeServiceMock;

    private $arrayData;

    protected function setUp(): void
    {
        parent::setUp();
        $this->permissionMock = Mockery::mock(Permission::class);
        $this->permissionRepositoryMock = Mockery::mock(PermissionRepository::class);
        $this->serializeServiceMock = Mockery::mock(SerializeService::class);
        $this->arrayData = ['name' => 'Permission 4', 'status' => Permission::$STATUS_NEW];
    }

    /**
     * @test
     */
    public function all()
    {
        $this->permissionRepositoryMock->shouldReceive('getAll')->andReturn([$this->permissionMock]);
        $this->serializeServiceMock->shouldReceive('normalize')->andReturn(array($this->permissionMock));
        $permissionService = new PermissionService($this->permissionRepositoryMock, $this->serializeServiceMock);
        $result = $permissionService->all($this->faker->name, $this->faker->name, $this->faker->randomDigit);

        $this->assertTrue(is_array($result));
    }

    /**
     * @test
     */
    public function totalUsers()
    {
        $this->permissionRepositoryMock->shouldReceive('getCountPermissions')->andReturn($count = 9);
        $permissionService = new PermissionService($this->permissionRepositoryMock, $this->serializeServiceMock);
        $result = $permissionService->totalPermissions($this->faker->email, $this->faker->name);

        $this->assertEquals($count, $result);
    }

    /**
     * @test
     */
    public function singleUser()
    {
        $this->permissionRepositoryMock->shouldReceive('get')->andReturn($this->permissionMock);
        $this->serializeServiceMock->shouldReceive('normalize')->andReturn(array($this->permissionMock));
        $permissionService = new PermissionService($this->permissionRepositoryMock, $this->serializeServiceMock);
        $result = $permissionService->single(1);

        $this->assertTrue(is_array($result));
    }

    /**
     * @test
     */
    public function createUser()
    {
        $this->permissionRepositoryMock->shouldReceive('findOneBy')->andReturn(null);
        $this->permissionRepositoryMock->shouldReceive('save')->andReturn(null);
        $permissionService = new PermissionService($this->permissionRepositoryMock, $this->serializeServiceMock);
        $result = $permissionService->create($this->arrayData);

        $typeObject = false;
        if ($result instanceof Permission) {
            $typeObject = true;
        }

        $this->assertTrue($typeObject);
        $this->assertEquals($this->arrayData['name'], $result->getName());
    }

    /**
     * @test
     */
    public function alreadyIssetName(): void
    {
        $this->permissionRepositoryMock->shouldReceive('findOneBy')->andReturn($this->permissionMock);
        $this->permissionRepositoryMock->shouldReceive('save')->andReturn(null);

        $this->expectException(\InvalidArgumentException::class);

        $permissionService = new PermissionService($this->permissionRepositoryMock, $this->serializeServiceMock);
        $permissionService->create($this->arrayData);
    }

    /**
     * @test
     */
    public function updateUser()
    {
        $this->permissionRepositoryMock->shouldReceive('get')->andReturn($this->permissionMock);
        $this->permissionMock->shouldReceive('setName')->andReturn($this->permissionMock);
        $this->permissionMock->shouldReceive('setStatus')->andReturn($this->permissionMock);
        $this->permissionMock->shouldReceive('onPreUpdate')->andReturn($this->permissionMock);
        $this->permissionMock->shouldReceive('getName')->andReturn($this->arrayData['name']);
        $this->permissionRepositoryMock->shouldReceive('save')->andReturn(null);
        $permissionService = new PermissionService($this->permissionRepositoryMock, $this->serializeServiceMock);
        $result = $permissionService->update($this->arrayData, $this->faker->randomDigit);

        $typeObject = false;
        if ($result instanceof Permission) {
            $typeObject = true;
        }

        $this->assertTrue($typeObject);
        $this->assertEquals($this->arrayData['name'], $result->getName());
    }

    /**
     * @test
     */
    public function deleteUser()
    {
        $this->permissionRepositoryMock->shouldReceive('get')->andReturn($this->permissionMock);
        $this->permissionRepositoryMock->shouldReceive('delete')->andReturn(null);
        $permissionService = new PermissionService($this->permissionRepositoryMock, $this->serializeServiceMock);
        $result = $permissionService->delete($this->faker->randomDigit);

        $this->assertTrue(is_null($result));
    }

}
