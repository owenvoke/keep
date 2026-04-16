<?php

declare(strict_types=1);

namespace App\Services\OIDC\Exceptions;

use InvalidArgumentException;

class OIDCConfigurationException extends InvalidArgumentException implements OIDCException {}
