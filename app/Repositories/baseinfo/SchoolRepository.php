<?php

namespace App\Repositories\baseinfo;

use App\Models\baseinfo\School;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class SchoolRepository.
 *
 * @package namespace App\Repositories\Baseinfo;
 */
class SchoolRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return School::class;
    }

    /**
     * 获取省
     * @return array
     * @author marhone
     */
    public function getProvinces(): array
    {
        $provinces = $this->model()::select('province')->distinct()->get()->toArray();
        return array_column($provinces, 'province');
    }

    /**
     * 获取省下的市级
     * @param string $province
     * @return array
     * @author marhone
     */
    public function getCities(string $province): array
    {
        $cites = $this->model()::select('city')
            ->distinct()
            ->where('province', $province)
            ->get()
            ->toArray();

        return array_column($cites, 'city');
    }

    /**
     * 获取市下的县级
     * @param string $city
     * @return array
     * @author marhone
     */
    public function getDistrict(string $city): array
    {
        $districts = $this->model()::select('district')
            ->distinct()
            ->where('city', $city)
            ->get()
            ->toArray();

        return array_column($districts, 'district');
    }

    /**
     * 获取该市的学校
     * @param $city
     * @return array
     * @author marhone
     */
    public function findByCity($city)
    {
        $schools = $this->model()::select('id', 'name')
            ->distinct()
            ->where('city', $city)
            ->paginate();

        return $schools;
    }

    /**
     * 获取该县的学校
     * @param string $city
     * @param string $district
     * @return array
     * @author marhone
     */
    public function findByDistrict(string $city, string $district)
    {
        $schools = $this->model()::select('id', 'name')
            ->distinct()
            ->where('city', $city)
            ->where('district', $district)
            ->paginate();

        return $schools;
    }
}
