<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Util;

class TokenGenerator implements TokenGeneratorInterface
{
    /**
     * @var string
     */
    protected $uniqueStr;

    /**
     * {@inheritdoc}
     */
    public function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=').md5(uniqid($this->uniqueStr, true));
    }

    public function setUniqueStr($uniqueStr)
    {
        $this->uniqueStr = $uniqueStr;

        return $this;
    }

}
