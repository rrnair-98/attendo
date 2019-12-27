<?php


namespace App\GraphQL\Exceptions;


use Exception;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Throwable;

class CustomException extends Exception implements RendersErrorsExtensions
{
    private $reason;
    public static $RESOURCE_ERROR = "Resource %s with property %s was %s";

    /**
     * CustomException constructor.
     * @param string $message The message
     * @param string $reason The reason
     */
    public function __construct(string $message, string $reason )
    {
        parent::__construct($message);

        $this->reason = $reason;
    }

    /**
     * Returns true if this exception is to displayed to a client
     * @return bool|void
     */
    public function isClientSafe(): bool
    {
        return true;
    }

    /**
     * returns the category of the message.
     * NOTE - graphql is reserved by the parser.
     * @return string
     */
    public function getCategory()
    {
        return 'custom';
    }

    /**
     * Returns addition content with the error
     * @return array
     */
    public function extensionsContent(): array
    {
        return [
            'reason' => $this->reason
        ];
    }




}
