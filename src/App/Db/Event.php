<?php
namespace App\Db;

use Tk\Db\Map\Model;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class Event extends Model implements \Tk\ValidInterface
{

    /**
     * @var int
     */
    public $id = 0;

    /**
     * @var string
     */
    public $title = '';

    /**
     * @var \DateTime
     */
    public $dateStart = null;


    /**
     * @var \DateTime
     */
    public $dateEnd = null;


    /**
     * @var string
     */
    public $rsvp = '';


    /**
     * @var string
     */
    public $description = '';

    /**
     * @var string
     */
    public $street = '';

    /**
     * @var string
     */
    public $city = '';

    /**
     * @var string
     */
    public $postcode = '';

    /**
     * @var string
     */
    public $state = '';

    /**
     * @var string
     */
    public $country = '';


    /**
     * @var string
     */
    public $address = '';

    /**
     * @var float
     */
    public $mapLat = -37.797441;

    /**
     * @var float
     */
    public $mapLng = 144.960773;

    /**
     * @var float
     */
    public $mapZoom = 14.0;

    /**
     * @var string
     */
    public $notes = '';

    /**
     * @var bool
     */
    public $active = true;

    /**
     * @var \DateTime
     */
    public $modified = null;

    /**
     * @var \DateTime
     */
    public $created = null;


    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->modified = new \DateTime();
        $this->created = new \DateTime();
        $this->dateStart = \Tk\Date::create()->add(new \DateInterval('P14D'));
        $this->dateEnd = (clone ($this->dateStart))->add(new \DateInterval('PT2H'));
    }

    /**
     *
     */
    public function save()
    {
        if (!$this->title) {
            $this->title = $this->dateStart->format('l, jS M Y') . ' - ' . $this->city . ', ' . $this->state;
        }
        parent::save();
    }


    /**
     * Validate this object's current state and return an array
     * with error messages. This will be useful for validating
     * objects for use within forms.
     *
     * @return array
     * @throws \Tk\Db\Exception
     */
    public function validate()
    {
        $errors = array();

//        if (!$this->title) {
//            $errors['title'] = 'Invalid title value';
//        }

        if (!$this->dateStart) {
            $errors['dateStart'] = 'Invalid dateStart value';
        }

        if (!$this->dateEnd) {
            $errors['dateEnd'] = 'Invalid dateEnd value';
        }

        if (!$this->rsvp) {
            $errors['rsvp'] = 'Invalid RSVP value';
        }

        if (!$this->address) {
            $errors['address'] = 'Invalid address value';
        }
        if ($this->street && !preg_match('/^.{1,128}$/', $this->street)) {
            $errors['address'][] = $errors['street'] = 'Invalid number and street Value.';
        }
        if ($this->city && !preg_match('/^.{3,128}$/', $this->city)) {
            $errors['address'][] = $errors['city'] = 'Invalid Field Value. No Abbreviations.';
        }
        if ($this->state && !preg_match('/^.{4,128}$/', $this->state)) {
            $errors['address'][] = $errors['state'] = 'Invalid Field Value. No Abbreviations.';
        }
        if ($this->country && !preg_match('/^.{4,128}$/', $this->country)) {
            $errors['address'][] = $errors['country'] = 'Invalid Field Value.';
        }
        if (!$this->street && !$this->city && !$this->state && !$this->country) {
            $errors['address'] = 'Please enter a valid address';
        }

        if (!$this->mapLat) {
            $errors['mapLat'] = 'Invalid mapLat value';
        }

        return $errors;
    }
}
