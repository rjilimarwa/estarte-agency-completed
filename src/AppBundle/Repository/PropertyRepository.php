<?php

namespace AppBundle\Repository;

/**
 * propertyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PropertyRepository extends \Doctrine\ORM\EntityRepository
{
    public function listBack($interval)
    {
        $date = new \DateTime();
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.category', 'ca')->addSelect('ca')
            ->leftJoin('e.operation', 'op')->addSelect('op')
            ->leftJoin('e.area', 'ar')->addSelect('ar')
            ->leftJoin('ar.city', 'ci')->addSelect('ci')
            ->leftJoin('e.image', 'img')->addSelect('img')
            ->orderBy('e.id', 'DESC');

        if ($interval == 'hier') {
            $date->modify('-1 day');
            $qb->andWhere('e.createdAt like :date')
                ->setParameter('date', '%' . $date->format('Y-m-d') . '%');
        } elseif ($interval == 'aujourdhui') {
            $qb->andWhere('e.createdAt like :today')
                ->setParameter('today', '%' . $date->format('Y-m-d') . '%');
        }
        elseif ($interval == 'waiting') {
            $qb->andWhere('e.state like :state')
                ->setParameter('state', '%waiting%');
        }
        elseif ($interval == 'valid') {
            $qb->andWhere('e.state like :state')
                ->setParameter('state', '%valid%');
        }
        elseif ($interval == 'rejected') {
            $qb->andWhere('e.state like :state')
                ->setParameter('state', '%rejected%');
        }

        return $qb;
    }

    public function countPropertyByUser($user){
        return $this->createQueryBuilder('e')
            ->select('COUNT(e)')
            ->where('e.user = :user')->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function StatisticProperties()
    {
        return $this->createQueryBuilder('p')
            ->select("sum(p.countViews) as countViews")
            ->addSelect("count(p.id) as countProperties")
            ->getQuery()->getSingleResult();
    }

    public function countPropertiesValid()
    {
        return $this->createQueryBuilder('p')
            ->select("count(p.id) as countValid")
            ->where('p.state = :state')
            ->setParameter('state', 'valid')
            ->getQuery()->getSingleResult();
    }

    public function countPropertiesRejected()
    {
        return $this->createQueryBuilder('p')
            ->select("count(p.id) as countRejected")
            ->where('p.state = :state')
            ->setParameter('state', 'rejected')
            ->getQuery()->getSingleResult();
    }

    public function countPropertiesWaiting()
    {
        return $this->createQueryBuilder('p')
            ->select("count(p.id) as countWaiting")
            ->where('p.state = :state')
            ->setParameter('state', 'waiting')
            ->getQuery()->getSingleResult();
    }

    public function countPropertiesOnline()
    {
        return $this->createQueryBuilder('p')
            ->select("count(p.id) as countOnline")
            ->where('p.state = :state')
            ->setParameter('state', 'valid')
            ->andWhere('p.active = 1')
            ->getQuery()->getSingleResult();
    }



    /*********************** front office ***********************/
    public function listAllFront()
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.category', 'ca')->addSelect('ca')
            ->leftJoin('e.operation', 'op')->addSelect('op')
            ->leftJoin('e.area', 'ar')->addSelect('ar')
            ->leftJoin('ar.city', 'ci')->addSelect('ci')
            ->leftJoin('e.image', 'img')->addSelect('img')
            ->where('e.active = 1')
            ->andWhere('e.state = :state')->setParameter('state', 'valid')
            ->orderBy('e.id', 'DESC');

        return $qb;
    }

    public function listForSale($idCat)
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.category', 'ca')->addSelect('ca')
            ->leftJoin('e.operation', 'op')->addSelect('op')
            ->leftJoin('e.area', 'ar')->addSelect('ar')
            ->leftJoin('ar.city', 'ci')->addSelect('ci')
            ->leftJoin('e.image', 'img')->addSelect('img')
            ->where('e.active = 1')
            ->andWhere('op.alias = :alias')->setParameter('alias', 'ven')
            ->andWhere('e.state = :state')->setParameter('state', 'valid')
            ->andWhere('ca.id = :idCat')->setParameter('idCat', $idCat)
            ->orderBy('e.id', 'DESC');
        return $qb;
    }

    public function listForRent($idCat)
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.category', 'ca')->addSelect('ca')
            ->leftJoin('e.operation', 'op')->addSelect('op')
            ->leftJoin('e.area', 'ar')->addSelect('ar')
            ->leftJoin('ar.city', 'ci')->addSelect('ci')
            ->leftJoin('e.image', 'img')->addSelect('img')
            ->where('e.active = 1')
            ->andWhere('op.alias = :alias')->setParameter('alias', 'loc')
            ->andWhere('e.state = :state')->setParameter('state', 'valid')
            ->andWhere('ca.id = :idCat')->setParameter('idCat', $idCat)
            ->orderBy('e.id', 'DESC');
        return $qb;
    }

    public function listByCategory($idCat)
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.category', 'ca')->addSelect('ca')
            ->leftJoin('e.operation', 'op')->addSelect('op')
            ->leftJoin('e.area', 'ar')->addSelect('ar')
            ->leftJoin('ar.city', 'ci')->addSelect('ci')
            ->leftJoin('e.image', 'img')->addSelect('img')
            ->where('e.active = 1')
            ->andWhere('e.state = :state')->setParameter('state', 'valid')
            ->andWhere('ca.id = :idCat')->setParameter('idCat', $idCat)
            ->orderBy('e.id', 'DESC');
        return $qb;
    }

    public function searchFront($data)
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.category', 'ca')->addSelect('ca')
            ->leftJoin('e.operation', 'op')->addSelect('op')
            ->leftJoin('e.area', 'ar')->addSelect('ar')
            ->leftJoin('ar.city', 'ci')->addSelect('ci')
            ->leftJoin('e.image', 'img')->addSelect('img')
            ->where('e.active = 1')
            ->andWhere('e.state = :state')->setParameter('state', 'valid')
            ->orderBy('e.id', 'DESC');

        if (!empty($data['ref'])) {
            $qb->andWhere('e.ref like :ref')
                ->setParameter('ref', '%' . $data['ref'] . '%');
        }
        if (!empty($data['keyWord'])) {
            $qb->andWhere('e.title like :title')
                ->setParameter('title', '%' . $data['keyWord'] . '%');
        }
        if (!empty($data['operation'])) {
            $qb->andWhere('op.name = :operation')
                ->setParameter('operation', $data['operation']);
        }
        if (!empty($data['category'])) {
            $qb->andWhere('ca.slug = :category')
                ->setParameter('category', $data['category']);
        }
        if (!empty($data['city'])) {
            $qb->andWhere('ci.slug = :city')
                ->setParameter('city', $data['city']);
        }
        if (!empty($data['area'])) {
            $qb->andWhere('ar.slug = :area')
                ->setParameter('area', $data['area']);
        }
        if (!empty($data['priceMin'])) {
            $qb->andWhere('e.price >= :priceMin')
                ->setParameter('priceMin', $data['priceMin']);
        }
        if (!empty($data['priceMax'])) {
            $qb->andWhere('e.price <= :priceMax')
                ->setParameter('priceMax', $data['priceMax']);
        }
        if (!empty($data['floorAreaMin'])) {
            $qb->andWhere('e.floorArea >= :floorAreaMin')
                ->setParameter('floorAreaMin', $data['floorAreaMin']);
        }
        if (!empty($data['floorAreaMax'])) {
            $qb->andWhere('e.floorArea <= :floorAreaMax')
                ->setParameter('floorAreaMax', $data['floorAreaMax']);
        }
        if (!empty($data['roomNumberMin'])) {
            $qb->andWhere('e.roomNumber >= :roomNumberMin')
                ->setParameter('roomNumberMin', $data['roomNumberMin']);
        }
        if (!empty($data['roomNumberMax'])) {
            $qb->andWhere('e.roomNumber <= :roomNumberMax')
                ->setParameter('roomNumberMax', $data['roomNumberMax']);
        }
        if (!empty($data['livingRoomNumber'])) {
            $qb->andWhere('e.livingRoomNumber <= :livingRoomNumber')
                ->setParameter('livingRoomNumber', $data['livingRoomNumber']);
        }
        if (!empty($data['bathRoomNumber'])) {
            $qb->andWhere('e.bathRoomNumber <= :bathRoomNumber')
                ->setParameter('bathRoomNumber', $data['bathRoomNumber']);
        }
        if (!empty($data['kitchenNumber'])) {
            $qb->andWhere('e.kitchenNumber <= :kitchenNumber')
                ->setParameter('kitchenNumber', $data['kitchenNumber']);
        }
        if (isset($data['garden'])) {
            $qb->andWhere('e.garden = 1');
        }
        if (isset($data['garage'])) {
            $qb->andWhere('e.garage = 1');
        }
        if (isset($data['parking'])) {
            $qb->andWhere('e.parking = 1');
        }
        if (isset($data['airConditioner'])) {
            $qb->andWhere('e.airConditioner = 1');
        }
        if (isset($data['heating'])) {
            $qb->andWhere('e.heating = 1');
        }
        if (isset($data['alarmSystem'])) {
            $qb->andWhere('e.alarmSystem = 1');
        }
        if (isset($data['elevator'])) {
            $qb->andWhere('e.elevator = 1');
        }
        return $qb;
    }

    public function similarProperties($id, $category)
    {
        $qb = $this->createQueryBuilder('e')
            ->leftJoin('e.category', 'cat')->addSelect('cat')
            ->leftJoin('e.operation', 'op')->addSelect('op')
            ->leftJoin('e.area', 'ar')->addSelect('ar')
            ->leftJoin('ar.city', 'ci')->addSelect('ci')
            ->leftJoin('e.image', 'img')->addSelect('img')
            ->where('e.active = 1')
            ->andWhere('e.state = :state')->setParameter('state', 'valid')
            ->andWhere('e.id != :idEntity')->setParameter('idEntity', $id)
            ->andWhere('cat.id = :idCat')->setParameter('idCat', $category)
            ->orderBy('e.id', 'DESC')
            ->setFirstResult(0)
            ->setMaxResults(3);;

        return $qb->getQuery()->getResult();
    }
}
