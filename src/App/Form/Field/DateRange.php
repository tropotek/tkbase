<?php
namespace App\Form\Field;


/**
 * @author Michael Mifsud <info@tropotek.com>
 * @see http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class DateRange extends \Tk\Form\Field\Iface
{

    /**
     * @param array|\ArrayObject $values
     * @return $this
     */
    public function load($values)
    {
        $v = array();
        if (isset($values['dateStart'])) {
            $v['dateStart'] =  $values['dateStart'];
        }
        if (isset($values['dateEnd'])) {
            $v['dateEnd'] =  $values['dateEnd'];
        }
        if (!count($v)) $v = null;
        $this->setValue($v);
        return $this;
    }

    
    /**
     * Get the element HTML
     *
     * @return string|\Dom\Template
     */
    public function show()
    {
        $t = $this->getTemplate();

        $this->decorateElement($t, 'dateStart');
        $this->decorateElement($t, 'dateEnd');

        $t->setAttr('dateStart', 'name', 'dateStart');
        $t->setAttr('dateEnd', 'name', 'dateEnd');
        $t->setAttr('dateStart', 'id', $this->getId().'Start');
        $t->setAttr('dateEnd', 'id', $this->getId().'End');

        // Set the field value
        $value = $this->getValue();
        if (is_array($value)) {
            $t->setAttr('dateStart', 'value', $value['dateStart']);
            $t->setAttr('dateEnd', 'value', $value['dateEnd']);
        }

        return $t;
    }

    /**
     * makeTemplate
     *
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {

        $xhtml = <<<HTML
<div class="input-group input-daterange">
    <input type="text" class="form-control dateStart" var="dateStart" data-parsley-error-message="Please enter a valid Start Date" />
    <span class="input-group-addon">to</span>
    <input type="text" class="form-control dateEnd" var="dateEnd" data-parsley-error-message="Please enter a valid End Date" />
</div>
HTML;
        return \Dom\Loader::load($xhtml);
    }
    
    
    
}