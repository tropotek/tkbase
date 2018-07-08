<?php
namespace App\Db;

use Tk\Db\Tool;
use Tk\Db\Map\ArrayObject;
use Tk\DataMap\Db;
use Tk\DataMap\Form;
use Tk\Db\Mapper;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class EventMap extends Mapper
{
    /**
     *
     * @return \Tk\DataMap\DataMap
     */
    public function getDbMap()
    {
        if (!$this->dbMap) {
            $this->setMarkDeleted('del');
            $this->dbMap = new \Tk\DataMap\DataMap();
            $this->dbMap->addPropertyMap(new Db\Integer('id'), 'key');
            $this->dbMap->addPropertyMap(new Db\Text('title'));
            $this->dbMap->addPropertyMap(new Db\Date('dateStart', 'date_start'));
            $this->dbMap->addPropertyMap(new Db\Date('dateEnd', 'date_end'));
            $this->dbMap->addPropertyMap(new Db\Text('rsvp'));
            $this->dbMap->addPropertyMap(new Db\Text('description'));
            $this->dbMap->addPropertyMap(new Db\Text('street'));
            $this->dbMap->addPropertyMap(new Db\Text('city'));
            $this->dbMap->addPropertyMap(new Db\Text('state'));
            $this->dbMap->addPropertyMap(new Db\Text('country'));
            $this->dbMap->addPropertyMap(new Db\Text('postcode'));
            $this->dbMap->addPropertyMap(new Db\Text('address'));
            $this->dbMap->addPropertyMap(new Db\Text('mapLat', 'map_lat'));
            $this->dbMap->addPropertyMap(new Db\Text('mapLng', 'map_lng'));
            $this->dbMap->addPropertyMap(new Db\Text('mapZoom', 'map_zoom'));
            $this->dbMap->addPropertyMap(new Db\Text('notes'));
            $this->dbMap->addPropertyMap(new Db\Boolean('active'));
            $this->dbMap->addPropertyMap(new Db\Date('modified'));
            $this->dbMap->addPropertyMap(new Db\Date('created'));

        }
        return $this->dbMap;
    }

    /**
     *
     * @return \Tk\DataMap\DataMap
     */
    public function getFormMap()
    {
        if (!$this->formMap) {
            $this->formMap = new \Tk\DataMap\DataMap();
            $this->formMap->addPropertyMap(new Form\Integer('id'), 'key');
            $this->formMap->addPropertyMap(new Form\Text('title'));
            $this->formMap->addPropertyMap(new Form\Date('dateStart'))->setDateFormat(\Tk\DataMap\Form\Date::FORMAT_DATETIME);
            $this->formMap->addPropertyMap(new Form\Date('dateEnd'))->setDateFormat(\Tk\DataMap\Form\Date::FORMAT_DATETIME);
            $this->formMap->addPropertyMap(new Form\Text('rsvp'));
            $this->formMap->addPropertyMap(new Form\Text('description'));
            $this->formMap->addPropertyMap(new Form\Text('street'));
            $this->formMap->addPropertyMap(new Form\Text('city'));
            $this->formMap->addPropertyMap(new Form\Text('state'));
            $this->formMap->addPropertyMap(new Form\Text('country'));
            $this->formMap->addPropertyMap(new Form\Text('postcode'));
            $this->formMap->addPropertyMap(new Form\Text('address'));
            $this->formMap->addPropertyMap(new Form\Text('mapLat'));
            $this->formMap->addPropertyMap(new Form\Text('mapLng'));
            $this->formMap->addPropertyMap(new Form\Text('mapZoom'));
            $this->formMap->addPropertyMap(new Form\Text('notes'));
            $this->formMap->addPropertyMap(new Form\Boolean('active'));
        }
        return $this->formMap;
    }

    /**
     * @param $email
     * @return \Tk\Db\Map\Model|Event
     * @throws \Tk\Db\Exception
     */
    public function findByEmail($email)
    {
        return $this->findFiltered(array('email' => $email))->current();
    }


    /**
     * Find filtered records
     *
     * @param array $filter
     * @param Tool $tool
     * @return ArrayObject|Event[]
     * @throws \Tk\Db\Exception
     */
    public function findFiltered($filter = array(), $tool = null)
    {
        $from = sprintf('%s a ', $this->getDb()->quoteParameter($this->getTable()));
        $where = '';

        if (!empty($filter['keywords'])) {
            $kw = '%' . $this->getDb()->escapeString($filter['keywords']) . '%';
            $w = '';
            $w .= sprintf('a.rsvp LIKE %s OR ', $this->getDb()->quote($kw));
            $w .= sprintf('a.city LIKE %s OR ', $this->quote($kw));
            $w .= sprintf('a.state LIKE %s OR ', $this->quote($kw));
            $w .= sprintf('a.country LIKE %s OR ', $this->quote($kw));
            $w .= sprintf('a.description LIKE %s OR ', $this->getDb()->quote($kw));
            if (is_numeric($filter['keywords'])) {
                $id = (int)$filter['keywords'];
                $w .= sprintf('a.id = %d OR ', $id);
            }
            if ($w) {
                $where .= '(' . substr($w, 0, -3) . ') AND ';
            }
        }

        if (!empty($filter['active']) && $filter['active'] !== null && $filter['active'] !== '') {
            $where .= sprintf('a.active = %s AND ', (int)$filter['active']);
        }
        if (!empty($filter['postcode'])) {
            $where .= sprintf('a.postcode = %s AND ', $this->getDb()->quote($filter['postcode']));
        }
        if (!empty($filter['country'])) {
            $where .= sprintf('a.country = %s AND ', $this->getDb()->quote($filter['country']));
        }
        if (!empty($filter['state'])) {
            $where .= sprintf('a.state = %s AND ', $this->getDb()->quote($filter['state']));
        }

        if (!empty($filter['_bounds'])) {
//            $bounds = \Map\Bounds::createFromString($filter['_bounds']);
//            // create boundry map query
//            $where .= sprintf('a.map_lat > %s AND a.map_lat < %s AND a.map_lng > %s AND a.map_lng < %s AND a.map_lng != 0 AND a.map_lat != 0 AND ',
//                round($bounds->getSouthWest()->lat(),4), round($bounds->getNorthEast()->lat(),4), round($bounds->getSouthWest()->lng(),4), round($bounds->getNorthEast()->lng(),4));
        }

        if (!empty($filter['dateStart']) && !empty($filter['dateEnd'])) {     // Contains
            /** @var \DateTime $dateStart */
            $dateStart = \Tk\Date::floor($filter['dateStart']);
            /** @var \DateTime $dateEnd */
            $dateEnd = \Tk\Date::floor($filter['dateEnd']);

            $where .= sprintf('((a.date_start >= %s AND ', $this->quote($dateStart->format(\Tk\Date::FORMAT_ISO_DATETIME)) );
            $where .= sprintf('a.date_start <= %s) OR ', $this->quote($dateEnd->format(\Tk\Date::FORMAT_ISO_DATETIME)) );

            $where .= sprintf('(a.date_end <= %s AND ', $this->quote($dateStart->format(\Tk\Date::FORMAT_ISO_DATETIME)) );
            $where .= sprintf('a.date_end >= %s)) AND ', $this->quote($dateEnd->format(\Tk\Date::FORMAT_ISO_DATETIME)) );

        } else if (!empty($filter['dateStart'])) {
            /** @var \DateTime $date */
            $date = \Tk\Date::floor($filter['dateStart']);
            $where .= sprintf('a.date_start >= %s AND ', $this->quote($date->format(\Tk\Date::FORMAT_ISO_DATETIME)) );
        } else if (!empty($filter['dateEnd'])) {
            /** @var \DateTime $date */
            $date = \Tk\Date::floor($filter['dateEnd']);
            $where .= sprintf('a.date_end <= %s AND ', $this->quote($date->format(\Tk\Date::FORMAT_ISO_DATETIME)) );
        }

        
        if (!empty($filter['exclude'])) {
            if (!is_array($filter['exclude'])) $filter['exclude'] = array($filter['exclude']);
            $w = '';
            foreach ($filter['exclude'] as $v) {
                $w .= sprintf('a.id != %d AND ', (int)$v);
            }
            if ($w) {
                $where .= ' ('. rtrim($w, ' AND ') . ') AND ';
            }
        }
        
        if ($where) {
            $where = substr($where, 0, -4);
        }

        $res = $this->selectFrom($from, $where, $tool);
        return $res;
    }
}