<?php
namespace X\Service\XView\Test\Core\Util\HtmlView;
/**
 * 
 */
use X\Core\Util\TestCase;
use X\Service\XView\Core\Util\HtmlView\MetaManager;
/**
 * 
 */
class MetaManagerTest extends TestCase {
    /**
     * 
     */
    public function test_MetaManager() {
        $manager = new MetaManager();
        $this->assertSame(null, $manager->toString());
        
        $expreTime = time()+100;
        
        $manager->setCharset();
        $manager->addKeyword('key1', 'key2');
        $manager->addKeyword('key3');
        $manager->refreshTo('http://www.example.com');
        $manager->addAuthor('Michael Luthor');
        $manager->addDescription('This is a test description.');
        $manager->setRevisitAfter(3);
        $manager->setCopyright('Copyright example.');
        $manager->setExpireTime($expreTime);
        $manager->setRobots(array('test1', 'test2'), 'google');
        $manager->disableTheClientCache();
        $manager->setOGTitle('OGTITLE');
        $manager->setOGAdmins('OGADMIN');
        $manager->setOGDescription('OGDESCRIPTION');
        $manager->setOGImage('OGIMAGE');
        $manager->setOGSiteName('OGSITENAME');
        $manager->setOGType('OGTYPE');
        $manager->setOGURL('http://www.ogurl.com');
        
        $metaString = array();
        $metaString[] = '<meta content="text/html; charset=UTF-8" http-equiv="content-type" />';
        $metaString[] = '<meta name="keywords" content="key1,key2,key3" />';
        $metaString[] = '<meta content="0; URL=http://www.example.com" http-equiv="refresh" />';
        $metaString[] = '<meta name="author" content="Michael Luthor" />';
        $metaString[] = '<meta name="description" content="This is a test description." />';
        $metaString[] = '<meta name="revisit-after" content="3 Days" />';
        $metaString[] = '<meta name="Copyright" content="Copyright example." />';
        $metaString[] = '<meta content="'.gmstrftime('%A %d %B %Y %H:%M GMT', $expreTime).'" http-equiv="expires" />';
        $metaString[] = '<meta name="google" content="test1,test2" />';
        $metaString[] = '<meta content="No-cach" http-equiv="Pragma" />';
        $metaString[] = '<meta property="og:title" content="OGTITLE" />';
        $metaString[] = '<meta property="og:admins" content="OGADMIN" />';
        $metaString[] = '<meta property="og:description" content="OGDESCRIPTION" />';
        $metaString[] = '<meta property="og:image" content="OGIMAGE" />';
        $metaString[] = '<meta property="og:site_name" content="OGSITENAME" />';
        $metaString[] = '<meta property="og:type" content="OGTYPE" />';
        $metaString[] = '<meta property="og:url" content="http://www.ogurl.com" />';
        $metaString = implode("\n", $metaString);
        $this->assertSame($metaString, $manager->toString());
    }
}