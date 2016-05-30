<?php
namespace App;

/**
 * Class Factory
 *
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2016 Michael Mifsud
 */
class Factory
{
    
    /**
     * Get Config object or array
     * 
     * @return \Tk\Config
     */
    public static function getConfig()
    {
        return \Tk\Config::getInstance();
    }
    
    /**
     * getDb
     * Ways to get the db after calling this method
     * 
     *  - \App\Factory::getDb()                 // Application level call
     *  - \Tk\Config::getInstance()->getDb()    // 
     *  - \Tk\Db\Pdo::getInstance()             // 
     * 
     * Note: If you are creating a base lib then the DB really should be sent in via a param or method.
     * 
     * @return mixed|\Tk\Db\Pdo
     */
    public static function getDb($name = 'default')
    {
        $config = self::getConfig();
        if (!$config->getDb() && $config->has('db.type')) {
            try {
                $pdo = \Tk\Db\Pdo::getInstance($name, $config->getGroup('db'));
                $logger = $config->getLog();
                if ($logger && $config->isDebug()) {
                    $pdo->setOnLogListener(function ($entry) use ($logger) {
                        $logger->debug('[' . round($entry['time'], 4) . 'sec] ' . $entry['query']);
                    });
                }
                $config->setDb($pdo);
            } catch (\Exception $e) {
                error_log('<p>' . $e->getMessage() . '</p>');
                exit;
            }
            self::getConfig()->setDb($pdo);
        }
        return self::getConfig()->getDb();
    }
    
    /**
     * get a dom Modifier object
     * 
     * @return \Dom\Modifier\Modifier
     */
    public static function getDomModifier()
    {
        if (!self::getConfig()->getDomModifier()) {
            $dm = new \Dom\Modifier\Modifier();
            $dm->add(new \Dom\Modifier\Filter\UrlPath(self::getConfig()->getSiteUrl()));
            $dm->add(new \Dom\Modifier\Filter\JsLast());
            self::getConfig()->setDomModifier($dm);
        }
        return self::getConfig()->getDomModifier();
    }

    /**
     * getDomLoader
     * 
     * @return \Dom\Loader
     */
    public static function getDomLoader()
    {   
        if (!self::getConfig()->getDomLoader()) {
            $dl = \Dom\Loader::getInstance()->setParams(self::getConfig()->all());
            $dl->addAdapter(new \Dom\Loader\Adapter\DefaultLoader());
            if (self::getConfig()->getTemplatePath()) {
                $dl->addAdapter(new \Dom\Loader\Adapter\ClassPath(self::getConfig()->getTemplatePath() . '/xtpl'));
            }
            self::getConfig()->setDomLoader($dl);
        }
        return self::getConfig()->getDomLoader();
    }
    
}