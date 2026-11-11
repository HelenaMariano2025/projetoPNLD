<?php

use Facebook\WebDriver\WebDriverBy;

require_once __DIR__ . '/E2ETestCase.php';

class ConexaoSeleniumTest extends E2ETestCase
{
    public function testDeveAbrirPaginaDeLogin(): void
    {
        $this->driver->get($this->baseUrl . '/login.php');

        $campoMatricula = $this->driver->findElement(
            WebDriverBy::name('matricula')
        );

        $campoSenha = $this->driver->findElement(
            WebDriverBy::name('senha')
        );

        $this->assertTrue(
            $campoMatricula->isDisplayed(),
            'O campo de matrícula deveria estar visível.'
        );

        $this->assertTrue(
            $campoSenha->isDisplayed(),
            'O campo de senha deveria estar visível.'
        );
    }
}