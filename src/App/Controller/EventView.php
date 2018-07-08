<?php
namespace App\Controller;

use Tk\Request;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class EventView extends \Bs\Controller\Iface
{
    /**
     * @var string
     */
    protected $templatePath = '';

    /**
     * @param Request $request
     */
    public function doDefault(Request $request)
    {

    }


    /**
     * @return \Dom\Template
     * @throws \Dom\Exception
     * @throws \Tk\Db\Exception
     * @throws \Tk\Exception
     */
    public function show()
    {
        $template = parent::show();

        $now = \Tk\Date::create();
        $now = \Tk\Date::floor($now);

        $list = \App\Db\EventMap::create()->findFiltered(array(
            'dateStart' => $now
        ), \Tk\Db\Tool::create('a.dateStart', 35));

        foreach ($list as $i => $event) {
            $row = $template->getRepeat('card');


            $url = \Tk\Uri::create('https://www.google.com/maps/embed/v1/place')
                ->set('key', $this->getConfig()->getGoogleMapKey())
                //->set('q', $event->address)
                ->set('q', $event->mapLat . ','. $event->mapLng)
                ->set('zoom', $event->mapZoom);
            $row->setAttr('gmap', 'src', $url);


            $title = $event->dateStart->format('l, jS M Y') . ' - ' . $event->city . ', ' . $event->state;
            $row->insertText('button', $title);
            $row->insertText('start', $event->dateStart->format('h:i A'));
            $row->insertText('end', $event->dateEnd->format('h:i A'));

            $row->insertText('address', $event->address);
            $row->insertText('rsvp', $event->rsvp);
            $row->insertHtml('description', $event->description);

            // ......

            if ($i == 0) {
                $row->addCss('collapse', 'show');
            }
            $row->setAttr('card-header', 'id', 'heading_'.$i);
            $row->setAttr('collapse', 'aria-labelledby', 'heading_'.$i);

            $row->setAttr('button', 'data-target', '#collapse_'.$i);
            $row->setAttr('button', 'data-controls', 'collapse_'.$i);
            $row->setAttr('collapse', 'id', 'collapse_'.$i);

            $row->appendRepeat();
        }

        $js = <<<JS
jQuery(function($) {
  
  $('#accordionExample').on('shown.bs.collapse', function (e) {
    $('html, body').stop().animate({
        scrollTop: $(e.target).offset().top - 250
    }, 500);
  });
  
});
JS;
        $template->appendJs($js);

        return $template;
    }



}