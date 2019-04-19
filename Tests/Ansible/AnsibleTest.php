<?php
/*
 * This file is part of the php-ansible package.
 *
 * (c) Marc Aschmann <maschmann@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Asm\Tests\Ansible;

use Asm\Ansible\Ansible;
use Asm\Ansible\Command\AnsibleGalaxy;
use Asm\Ansible\Command\AnsiblePlaybook;
use Asm\Ansible\Exception\CommandException;
use Asm\Test\AnsibleTestCase;
use org\bovigo\vfs\vfsStream;

class AnsibleTest extends AnsibleTestCase
{
    /**
     * @covers \Asm\Ansible\Ansible::checkCommand
     * @covers \Asm\Ansible\Ansible::checkDir
     * @covers \Asm\Ansible\Ansible::__construct
     */
    public function testInstance(): void
    {
        $ansible = new Ansible(
            $this->getProjectUri(),
            $this->getPlaybookUri(),
            $this->getGalaxyUri()
        );
        $this->assertInstanceOf(Ansible::class, $ansible, 'Instantiation with given paths');
    }

    /**
     * @expectedException CommandException
     * @covers \Asm\Ansible\Ansible::checkCommand
     * @covers \Asm\Ansible\Ansible::checkDir
     * @covers \Asm\Ansible\Ansible::__construct
     */
    public function testAnsibleProjectPathNotFoundException(): void
    {
        $ansible = new Ansible(
            'xxxxxxxx',
            $this->getPlaybookUri(),
            $this->getGalaxyUri()
        );
    }

    /**
     * @expectedException CommandException
     * @covers \Asm\Ansible\Ansible::checkCommand
     * @covers \Asm\Ansible\Ansible::checkDir
     * @covers \Asm\Ansible\Ansible::__construct
     */
    public function testAnsibleCommandNotFoundException(): void
    {
        $ansible = new Ansible(
            $this->getProjectUri(),
            '/tmp/ansible-playbook',
            '/tmp/ansible-galaxy'
        );
    }

    /**
     * @expectedException CommandException
     * @covers \Asm\Ansible\Ansible::checkCommand
     * @covers \Asm\Ansible\Ansible::checkDir
     * @covers \Asm\Ansible\Ansible::__construct
     */
    public function testAnsibleNoCommandGivenException(): void
    {
        $ansible = new Ansible(
            $this->getProjectUri()
        );
    }

    /**
     * @expectedException CommandException
     * @covers \Asm\Ansible\Ansible::checkCommand
     * @covers \Asm\Ansible\Ansible::checkDir
     * @covers \Asm\Ansible\Ansible::__construct
     */
    public function testAnsibleCommandNotExecutableException(): void
    {
        $vfs = vfsStream::setup('/tmp');
        $ansiblePlaybook = vfsStream::newFile('ansible-playbook', 600)->at($vfs);
        $ansibleGalaxy = vfsStream::newFile('ansible-galaxy', 444)->at($vfs);

        $ansible = new Ansible(
            $this->getProjectUri(),
            $ansiblePlaybook->url(),
            $ansibleGalaxy->url()
        );
    }

    /**
     * @covers \Asm\Ansible\Ansible::playbook
     * @covers \Asm\Ansible\Ansible::createProcess
     * @covers \Asm\Ansible\Ansible::checkCommand
     * @covers \Asm\Ansible\Ansible::checkDir
     * @covers \Asm\Ansible\Ansible::__construct
     */
    public function testPlaybookCommandInstance(): void
    {
        $ansible = new Ansible(
            $this->getProjectUri(),
            $this->getPlaybookUri(),
            $this->getGalaxyUri()
        );

        $playbook = $ansible->playbook();

        $this->assertInstanceOf(AnsiblePlaybook::class, $playbook);
    }

    /**
     * @covers \Asm\Ansible\Ansible::galaxy
     * @covers \Asm\Ansible\Ansible::createProcess
     * @covers \Asm\Ansible\Ansible::checkCommand
     * @covers \Asm\Ansible\Ansible::checkDir
     * @covers \Asm\Ansible\Ansible::__construct
     */
    public function testGalaxyCommandInstance(): void
    {
        $ansible = new Ansible(
            $this->getProjectUri(),
            $this->getPlaybookUri(),
            $this->getGalaxyUri()
        );

        $galaxy = $ansible->galaxy();

        $this->assertInstanceOf(AnsibleGalaxy::class, $galaxy);
    }
}
