<?php

namespace App\Controller;

use App\Entity\Domains;
use App\Entity\Repository\DomainsRepository;
use App\Filesystem\Interfaces\ConfigInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class CoreController extends AbstractController implements ConfigInterface
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $em;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @Route("/core", name="app_core")
     */
    public function index(): Response
    {
        return $this->render('core/index.html.twig', [
            'controller_name' => 'CoreController',
        ]);
    }

    /**
     * @return array
     */
    protected function listDomains(): array
    {
        return $this->sanitizeDomainList(scandir(ConfigInterface::ABS_PATH_TO_SITES_AVAILABLE));
    }

    /**
     * @param array $domainList
     * @return array
     */
    private function sanitizeDomainList(array $domainList): array
    {
        $domainsArray = [];
        foreach ($domainList as $domain) {
            $domainsArray[] = $this->sanitizeConf($domain);
        }
        return array_diff($domainsArray, array('..', '.'));
    }

    /**
     * @return void
     */
    public function checkAndUpdateDomainsList()
    {
        $availableConfigList = array_diff(scandir(ConfigInterface::ABS_PATH_TO_SITES_AVAILABLE), array('..', '.'));
        $existingDomainsRepository = $this->em->getRepository(DomainsRepository::class);


        foreach ($availableConfigList as $conf) {
            $existingDomain = $existingDomainsRepository->findBy(['name' => $this->sanitizeConf($conf)]);

            if (!$existingDomain) {
                $documentRoot = $this->findInFile(
                    ConfigInterface::VHOST_DOCUMENT_ROOT_NODE,
                    ConfigInterface::ABS_PATH_TO_SITES_AVAILABLE . $conf
                );
                $domainsEntity = new Domains;
                $domainsEntity->setName($this->sanitizeConf($conf));
                $domainsEntity->setDocumentRoot($documentRoot);
                $domainsEntity->setVhostContent(ConfigInterface::ABS_PATH_TO_SITES_AVAILABLE . $conf);
                $domainsEntity->setUserId($this->getCurrentUserId());
                $domainsEntity->setIsEnabled($this->isDomainEnabled($conf));
                $domainsEntity->setCreatedAt(new \DateTimeImmutable('now'));
            }
        }
    }

    /**
     * @param $string
     * @param $absFilePath
     * @return string|void
     */
    private function findInFile($string, $absFilePath)
    {
        $lines = file($absFilePath);
        foreach ($lines as $line) {
            $arr = explode($string, $line, 3);
            if (isset($arr[1])) {
                return $arr[1];
            }
        }
    }

    /**
     * @param $name
     * @return array|string|string[]
     */
    public function sanitizeConf($name)
    {
        return str_replace('.conf', '', $name);
    }

    /**
     * @param $filepath
     * @return false|string
     */
    public function getFileContent($filepath)
    {
        return file_get_contents($filepath);
    }

    /**
     * @return integer
     */
    public function getCurrentUserId(): int
    {
        return $this->getUser()->getId();
    }

    /**
     * @param $domainName
     * @return bool
     */
    public function isDomainEnabled($domainName): bool
    {
        $result = false;
        $enabledDirListFile = $this->lsDir(ConfigInterface::ABS_PATH_TO_SITES_ENABLED);
        if(in_array($domainName, $enabledDirListFile)) {
            $result = true;
        }

        return $result;
    }

    /**
     * List Directories
     *
     * @param $absPath
     * @param bool $withHidden
     * @return array|false
     */
    public function lsDir($absPath, bool $withHidden = false)
    {
        $result = scandir($absPath);
        if ($withHidden) {
            $result = array_diff($result, array('..', '.'));
        }
        return $result;
    }
}
