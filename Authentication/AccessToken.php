<?php
namespace iiko\Authentication;

class AccessToken
{
    CONST DEFAULT_TTL_SECONDS = 15 * 60;
    protected $value;

    /**
     * Date when token expires.
     *
     * @var \DateTime|null
     */
    protected $expAt;

    /**
     * AccessToken constructor.
     * @param string $accessToken
     * @param int seconds $ttl
     */
    public function __construct($accessToken, $ttl=0)
    {
        $this->value = $accessToken;
        $ttl = $ttl ? $ttl : self::DEFAULT_TTL_SECONDS;
        $this->setExpiresAtFromTimeStamp($ttl);
    }

    /**
     * Getter for expiresAt.
     *
     * @return \DateTime|null
     */
    public function getExpiresAt()
    {
        return $this->expAt;
    }

    /**
     * Returns the access token as a string.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Checks the expiration of the access token.
     *
     * @return boolean|null
     */
    public function isExpired()
    {
        if ($this->getExpiresAt() instanceof \DateTime) {
            return $this->getExpiresAt()->getTimestamp() < time();
        }
        return null;
    }


    /**
     * Returns the access token as a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }

    /**
     * Setter for expires_at.
     *
     * @param int $timeStamp
     */
    public function setExpiresAtFromTimeStamp($ttl)
    {
        $date = new \DateTime();
        $dv = new \DateInterval('PT'.$ttl.'S');
        $date->add($dv); // adds ttl secs
        $this->expAt = $date;
    }

}