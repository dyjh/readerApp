<?php

namespace App\Http\Controllers\api\baseinfo;

use App\Http\Resources\baseinfo\SchoolCollection;
use App\Repositories\baseinfo\SchoolRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SchoolsController extends Controller
{
    /**
     * @var SchoolRepository
     */
    private $repository;

    /**
     * SchoolsController constructor.
     * @param SchoolRepository $repository
     */
    public function __construct(SchoolRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * 获取省名称列表
     * @return \Illuminate\Http\JsonResponse
     * @author marhone
     */
    public function provinces()
    {
        return response()->json([
            'status' => 1,
            'data' => [
                'provinces' => $this->repository->getProvinces()
            ]
        ]);
    }

    /**
     * 获取省下的城市名列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author marhone
     */
    public function cities(Request $request)
    {
        $province = $request->get('province');
        if (empty($province)) {
            return response()->json([
                'status' => -1,
                'message' => '省名称[province]必传.'
            ]);
        }

        return response()->json([
            'status' => 1,
            'data' => [
                'cities' => $this->repository->getCities($province)
            ]
        ]);
    }

    /**
     * 获取县名列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author marhone
     */
    public function districts(Request $request)
    {
        $city = $request->get('city', null);
        if (empty($city)) {
            return response()->json([
                'status' => -1,
                'message' => '市名称[city]必传.'
            ]);
        }

        return response()->json([
            'status' => 1,
            'data' => [
                'cities' => $this->repository->getDistrict($city)
            ]
        ]);
    }

    /**
     * 获取市,县下的城市列表
     * @param Request $request
     * @return SchoolCollection
     * @author marhone
     */
    public function schools(Request $request)
    {
        $city = $request->get('city');
        $district = $request->get('district');
        if (empty($city)) {
            return response()->json([
                'status' => -1,
                'message' => '市,县名称[city,district]必传.'
            ]);
        }

        if (!empty($district)) {
            $schools = $this->repository->findByDistrict($city, $district);
        } else {
            $schools = $this->repository->findByCity($city);
        }

        return new SchoolCollection($schools);
    }
}
