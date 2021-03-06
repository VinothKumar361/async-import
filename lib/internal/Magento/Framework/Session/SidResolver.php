<?php
/**
 * SID resolver
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\Session;

use Magento\Framework\App\State;

/**
 * Resolves SID by processing request parameters.
 *
 * @deprecated 102.0.2 SIDs in URLs are no longer used
 */
class SidResolver implements SidResolverInterface
{
    /**
     * Config path for flag whether use SID on frontend
     */
    const XML_PATH_USE_FRONTEND_SID = 'web/session/use_frontend_sid';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\UrlInterface
     * @deprecated Not used anymore.
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Framework\App\RequestInterface
     * @deprecated Not used anymore.
     */
    protected $request;

    /**
     * @var array
     */
    protected $sidNameMap;

    /**
     * Use session var instead of SID for session in URL
     *
     * @var bool
     */
    protected $_useSessionVar = false;

    /**
     * Use session in URL flag
     *
     * @var bool|null
     * @see \Magento\Framework\UrlInterface
     */
    protected $_useSessionInUrl;

    /**
     * @var string
     */
    protected $_scopeType;

    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Framework\App\RequestInterface $request
     * @param string $scopeType
     * @param array $sidNameMap
     * @param State|null $appState
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\App\RequestInterface $request,
        $scopeType,
        array $sidNameMap = [],
        State $appState = null
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->urlBuilder = $urlBuilder;
        $this->request = $request;
        $this->sidNameMap = $sidNameMap;
        $this->_scopeType = $scopeType;
    }

    /**
     * Get Sid
     *
     * @param SessionManagerInterface $session
     * @return string|null
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getSid(SessionManagerInterface $session)
    {
        return null;
    }

    /**
     * Get session id query param
     *
     * @param SessionManagerInterface $session
     * @return string
     */
    public function getSessionIdQueryParam(SessionManagerInterface $session)
    {
        $sessionName = $session->getName();
        if ($sessionName && isset($this->sidNameMap[$sessionName])) {
            return $this->sidNameMap[$sessionName];
        }
        return self::SESSION_ID_QUERY_PARAM;
    }

    /**
     * Set use session var instead of SID for URL
     *
     * @param bool $var
     * @return $this
     */
    public function setUseSessionVar($var)
    {
        $this->_useSessionVar = (bool)$var;
        return $this;
    }

    /**
     * Retrieve use flag session var instead of SID for URL
     *
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getUseSessionVar()
    {
        return $this->_useSessionVar;
    }

    /**
     * Set Use session in URL flag
     *
     * @param bool $flag
     * @return $this
     */
    public function setUseSessionInUrl($flag = true)
    {
        $this->_useSessionInUrl = (bool)$flag;
        return $this;
    }

    /**
     * Retrieve use session in URL flag.
     *
     * @return bool
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getUseSessionInUrl()
    {
        if ($this->_useSessionInUrl === null) {
            //Using config value by default, can be overridden by using the
            //setter.
            $this->_useSessionInUrl = $this->scopeConfig->isSetFlag(
                self::XML_PATH_USE_FRONTEND_SID,
                $this->_scopeType
            );
        }

        return $this->_useSessionInUrl;
    }
}
