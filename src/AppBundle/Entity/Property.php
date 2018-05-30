<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Property
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PropertyRepository")
 * @ORM\Table(name="property")
 */
class Property
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", unique=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="ref", type="string", length=20, nullable=true)
     *
     */
    protected $ref;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * @Gedmo\Slug(fields={"title"})
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var bool
     *
     * @ORM\Column(name="display_price", type="boolean")
     */
    private $displayPrice;

    /**
     * @var int
     *
     * @ORM\Column(name="floor_area", type="integer", nullable=true)
     */
    private $floorArea;

    /**
     * @var int
     *
     * @ORM\Column(name="plot_area", type="integer", nullable=true)
     */
    private $plotArea;

    /**
     * @var int
     *
     * @ORM\Column(name="room_number", type="integer", nullable=true)
     */
    private $roomNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="living_room_number", type="integer", nullable=true)
     */
    private $livingRoomNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="bath_room_number", type="integer", nullable=true)
     */
    private $bathRoomNumber;

    /**
     * @var int
     *
     * @ORM\Column(name="kitchen_number", type="integer", nullable=true)
     */
    private $kitchenNumber;

    /**
     * @var bool
     *
     * @ORM\Column(name="balcony", type="boolean")
     */
    private $balcony;

    /**
     * @var int
     *
     * @ORM\Column(name="terrace", type="integer", nullable=true)
     */
    private $terrace;

    /**
     * @var bool
     *
     * @ORM\Column(name="garden", type="boolean")
     */
    private $garden;

    /**
     * @var bool
     *
     * @ORM\Column(name="garage", type="boolean")
     */
    private $garage;

    /**
     * @var bool
     *
     * @ORM\Column(name="parking", type="boolean")
     */
    private $parking;

    /**
     * @var int
     *
     * @ORM\Column(name="floor_number", type="integer", nullable=true)
     */
    private $floorNumber;

    /**
     * @var bool
     *
     * @ORM\Column(name="elevator", type="boolean")
     */
    private $elevator;

    /**
     * @var bool
     *
     * @ORM\Column(name="heating", type="boolean")
     */
    private $heating;

    /**
     * @var bool
     *
     * @ORM\Column(name="air_conditioner", type="boolean")
     */
    private $airConditioner;

    /**
     * @var bool
     *
     * @ORM\Column(name="alarm_system", type="boolean")
     */
    private $alarmSystem;

    /**
     * @var bool
     *
     * @ORM\Column(name="wifi", type="boolean")
     */
    private $wifi;

    /**
     * @var bool
     *
     * @ORM\Column(name="swimming_pool", type="boolean")
     */
    private $swimmingPool;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active; //by owner

    /**
     * @var bool
     *
     * @ORM\Column(name="state", type="string", length=10)
     */
    private $state; //by administrator

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="count_views", type="integer")
     */
    private $countViews;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Situation")
     * @ORM\JoinColumn(nullable=true)
     */
    private $situation;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\City")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Area")
     * @ORM\JoinColumn(nullable=true)
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Operation")
     * @ORM\JoinColumn(nullable=true)
     */
    private $operation;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Photo", mappedBy="property", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $photos;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\PropertyFavorite", mappedBy="property", cascade={"remove"})
     */
    private $favorites;

    public function __construct()
    {
        $this->countViews    = 0;
        $this->createdAt     = new \DateTime();
        $this->favorites     = new ArrayCollection();
        $this->state         = 'waiting';
        $this->photos        = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return Property
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set ref
     *
     * @param string $ref
     *
     * @return Property
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Property
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Property
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Property
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param integer $price
     *
     * @return Property
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return integer
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set displayPrice
     *
     * @param boolean $displayPrice
     *
     * @return Property
     */
    public function setDisplayPrice($displayPrice)
    {
        $this->displayPrice = $displayPrice;

        return $this;
    }

    /**
     * Get displayPrice
     *
     * @return boolean
     */
    public function getDisplayPrice()
    {
        return $this->displayPrice;
    }

    /**
     * Set floorArea
     *
     * @param integer $floorArea
     *
     * @return Property
     */
    public function setFloorArea($floorArea)
    {
        $this->floorArea = $floorArea;

        return $this;
    }

    /**
     * Get floorArea
     *
     * @return integer
     */
    public function getFloorArea()
    {
        return $this->floorArea;
    }

    /**
     * Set plotArea
     *
     * @param integer $plotArea
     *
     * @return Property
     */
    public function setPlotArea($plotArea)
    {
        $this->plotArea = $plotArea;

        return $this;
    }

    /**
     * Get plotArea
     *
     * @return integer
     */
    public function getPlotArea()
    {
        return $this->plotArea;
    }

    /**
     * Set roomNumber
     *
     * @param integer $roomNumber
     *
     * @return Property
     */
    public function setRoomNumber($roomNumber)
    {
        $this->roomNumber = $roomNumber;

        return $this;
    }

    /**
     * Get roomNumber
     *
     * @return integer
     */
    public function getRoomNumber()
    {
        return $this->roomNumber;
    }

    /**
     * Set livingRoomNumber
     *
     * @param integer $livingRoomNumber
     *
     * @return Property
     */
    public function setLivingRoomNumber($livingRoomNumber)
    {
        $this->livingRoomNumber = $livingRoomNumber;

        return $this;
    }

    /**
     * Get livingRoomNumber
     *
     * @return integer
     */
    public function getLivingRoomNumber()
    {
        return $this->livingRoomNumber;
    }

    /**
     * Set bathRoomNumber
     *
     * @param integer $bathRoomNumber
     *
     * @return Property
     */
    public function setBathRoomNumber($bathRoomNumber)
    {
        $this->bathRoomNumber = $bathRoomNumber;

        return $this;
    }

    /**
     * Get bathRoomNumber
     *
     * @return integer
     */
    public function getBathRoomNumber()
    {
        return $this->bathRoomNumber;
    }

    /**
     * Set kitchenNumber
     *
     * @param integer $kitchenNumber
     *
     * @return Property
     */
    public function setKitchenNumber($kitchenNumber)
    {
        $this->kitchenNumber = $kitchenNumber;

        return $this;
    }

    /**
     * Get kitchenNumber
     *
     * @return integer
     */
    public function getKitchenNumber()
    {
        return $this->kitchenNumber;
    }

    /**
     * Set balcony
     *
     * @param boolean $balcony
     *
     * @return Property
     */
    public function setBalcony($balcony)
    {
        $this->balcony = $balcony;

        return $this;
    }

    /**
     * Get balcony
     *
     * @return boolean
     */
    public function getBalcony()
    {
        return $this->balcony;
    }

    /**
     * Set terrace
     *
     * @param integer $terrace
     *
     * @return Property
     */
    public function setTerrace($terrace)
    {
        $this->terrace = $terrace;

        return $this;
    }

    /**
     * Get terrace
     *
     * @return integer
     */
    public function getTerrace()
    {
        return $this->terrace;
    }

    /**
     * Set garden
     *
     * @param boolean $garden
     *
     * @return Property
     */
    public function setGarden($garden)
    {
        $this->garden = $garden;

        return $this;
    }

    /**
     * Get garden
     *
     * @return boolean
     */
    public function getGarden()
    {
        return $this->garden;
    }

    /**
     * Set garage
     *
     * @param boolean $garage
     *
     * @return Property
     */
    public function setGarage($garage)
    {
        $this->garage = $garage;

        return $this;
    }

    /**
     * Get garage
     *
     * @return boolean
     */
    public function getGarage()
    {
        return $this->garage;
    }

    /**
     * Set parking
     *
     * @param boolean $parking
     *
     * @return Property
     */
    public function setParking($parking)
    {
        $this->parking = $parking;

        return $this;
    }

    /**
     * Get parking
     *
     * @return boolean
     */
    public function getParking()
    {
        return $this->parking;
    }

    /**
     * Set floorNumber
     *
     * @param integer $floorNumber
     *
     * @return Property
     */
    public function setFloorNumber($floorNumber)
    {
        $this->floorNumber = $floorNumber;

        return $this;
    }

    /**
     * Get floorNumber
     *
     * @return integer
     */
    public function getFloorNumber()
    {
        return $this->floorNumber;
    }

    /**
     * Set elevator
     *
     * @param boolean $elevator
     *
     * @return Property
     */
    public function setElevator($elevator)
    {
        $this->elevator = $elevator;

        return $this;
    }

    /**
     * Get elevator
     *
     * @return boolean
     */
    public function getElevator()
    {
        return $this->elevator;
    }

    /**
     * Set heating
     *
     * @param boolean $heating
     *
     * @return Property
     */
    public function setHeating($heating)
    {
        $this->heating = $heating;

        return $this;
    }

    /**
     * Get heating
     *
     * @return boolean
     */
    public function getHeating()
    {
        return $this->heating;
    }

    /**
     * Set airConditioner
     *
     * @param boolean $airConditioner
     *
     * @return Property
     */
    public function setAirConditioner($airConditioner)
    {
        $this->airConditioner = $airConditioner;

        return $this;
    }

    /**
     * Get airConditioner
     *
     * @return boolean
     */
    public function getAirConditioner()
    {
        return $this->airConditioner;
    }

    /**
     * Set alarmSystem
     *
     * @param boolean $alarmSystem
     *
     * @return Property
     */
    public function setAlarmSystem($alarmSystem)
    {
        $this->alarmSystem = $alarmSystem;

        return $this;
    }

    /**
     * Get alarmSystem
     *
     * @return boolean
     */
    public function getAlarmSystem()
    {
        return $this->alarmSystem;
    }

    /**
     * Set wifi
     *
     * @param boolean $wifi
     *
     * @return Property
     */
    public function setWifi($wifi)
    {
        $this->wifi = $wifi;

        return $this;
    }

    /**
     * Get wifi
     *
     * @return boolean
     */
    public function getWifi()
    {
        return $this->wifi;
    }

    /**
     * Set swimmingPool
     *
     * @param boolean $swimmingPool
     *
     * @return Property
     */
    public function setSwimmingPool($swimmingPool)
    {
        $this->swimmingPool = $swimmingPool;

        return $this;
    }

    /**
     * Get swimmingPool
     *
     * @return boolean
     */
    public function getSwimmingPool()
    {
        return $this->swimmingPool;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Property
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Property
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Property
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set countViews
     *
     * @param integer $countViews
     *
     * @return Property
     */
    public function setCountViews($countViews)
    {
        $this->countViews = $countViews;

        return $this;
    }

    /**
     * Get countViews
     *
     * @return integer
     */
    public function getCountViews()
    {
        return $this->countViews;
    }

    /**
     * Set situation
     *
     * @param \AppBundle\Entity\Situation $situation
     *
     * @return Property
     */
    public function setSituation(\AppBundle\Entity\Situation $situation = null)
    {
        $this->situation = $situation;

        return $this;
    }

    /**
     * Get situation
     *
     * @return \AppBundle\Entity\Situation
     */
    public function getSituation()
    {
        return $this->situation;
    }

    /**
     * Set city
     *
     * @param \AppBundle\Entity\City $city
     *
     * @return Property
     */
    public function setCity(\AppBundle\Entity\City $city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return \AppBundle\Entity\City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set area
     *
     * @param \AppBundle\Entity\Area $area
     *
     * @return Property
     */
    public function setArea(\AppBundle\Entity\Area $area = null)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return \AppBundle\Entity\Area
     */
    public function getArea()
    {
        return $this->area;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Property
     */
    public function setUser(\AppBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set operation
     *
     * @param \AppBundle\Entity\Operation $operation
     *
     * @return Property
     */
    public function setOperation(\AppBundle\Entity\Operation $operation = null)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation
     *
     * @return \AppBundle\Entity\Operation
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Property
     */
    public function setCategory(\AppBundle\Entity\Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Property
     */
    public function setImage(\AppBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \AppBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add photo
     *
     * @param \AppBundle\Entity\Photo $photo
     *
     * @return Property
     */
    public function addPhoto(\AppBundle\Entity\Photo $photo)
    {
        $this->photos[] = $photo;
        $photo->setProperty($this);
        
        return $this;
    }

    /**
     * Remove photo
     *
     * @param \AppBundle\Entity\Photo $photo
     */
    public function removePhoto(\AppBundle\Entity\Photo $photo)
    {
        $this->photos->removeElement($photo);
    }

    /**
     * Get photos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Add favorite
     *
     * @param \AppBundle\Entity\PropertyFavorite $favorite
     *
     * @return Property
     */
    public function addFavorite(\AppBundle\Entity\PropertyFavorite $favorite)
    {
        $this->favorites[] = $favorite;

        return $this;
    }

    /**
     * Remove favorite
     *
     * @param \AppBundle\Entity\PropertyFavorite $favorite
     */
    public function removeFavorite(\AppBundle\Entity\PropertyFavorite $favorite)
    {
        $this->favorites->removeElement($favorite);
    }

    /**
     * Get favorites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFavorites()
    {
        return $this->favorites;
    }

}
