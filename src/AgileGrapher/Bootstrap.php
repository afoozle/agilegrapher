<?php
namespace AgileGrapher;
define('BASEDIR',dirname(dirname(__DIR__)));
require_once BASEDIR . '/src/library/Ergo/classes/Ergo/ClassLoader.php';

global $BOOTSTRAP;
$BOOTSTRAP = new Bootstrap();
$BOOTSTRAP->bootstrap();

class Bootstrap
{
    public function bootstrap() {
        $this->initAutoloader();
        $this->initDb();
    }

    /**
     * @var \Ergo\ClassLoader
     */
    protected $classLoader;

    /**
     * @Var \Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @return \Ergo\ClassLoader
     */
    public function getClassLoader() {
        return $this->classLoader;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager() {
        return $this->entityManager;
    }

    public function initAutoloader() {
        $this->classLoader = new \Ergo\ClassLoader();
        $this->classLoader->register()->includePaths(
            array(
                BASEDIR."/src/library/Ergo/classes",
                BASEDIR."/src/",
                "/usr/share/php"
            )
        );
    }

    public function initDb() {
        $config = new \Doctrine\ORM\Configuration();
        $config->setProxyDir(BASEDIR.'/src/AgileGrapher/Data/Proxy');
        $config->setProxyNamespace('AgileGrapher\Data\Proxy');
        $config->setAutoGenerateProxyClasses(true); // FIXME, Dev Only

        $driver = $config->newDefaultAnnotationDriver(BASEDIR.'/src/AgileGrapher/Model');
        $config->setMetadataDriverImpl($driver);

        $cache = new \Doctrine\Common\Cache\ArrayCache();
        $config->setMetaDataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
        $dbConnection = array(
            'driver' => 'pdo_sqlite',
            'path' => BASEDIR.'/db/agilegrapher.db'
        );
        $eventManager = new \Doctrine\Common\EventManager();
        $this->entityManager = \Doctrine\ORM\EntityManager::create($dbConnection, $config, $eventManager);
    }
}

