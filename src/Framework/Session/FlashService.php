<?php
namespace Framework\Session;


class FlashService
{

    /**
     * @var SessionInterface
     */
    private $session;


    private $sessionKey = 'flash';


    private $message;

    /**
     * FlashService constructor.
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param string $message
     */
    public function success(string $message)
    {
        $flash = $this->session->get($this->sessionKey, []);
        $flash['success'] = $message;
        $this->session->set($this->sessionKey, $flash);
    }


    /**
     * @param string $type
     * @return string|null
     */
    public function get(string $type): ?string
    {
        if (is_null($this->message)) {
            $flash = $this->session->get($this->sessionKey, []);
            $this->message = $flash;
            $this->session->delete($this->sessionKey);
        }

        if (array_key_exists($type, $this->message)) {
            return $this->message[$type];
        }
        return null;
    }
}
