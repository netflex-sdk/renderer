<?php

namespace Netflex\Render\Exceptions;

use Exception;

use Illuminate\Support\Str;

use Netflex\Http\Concerns\ParsesResponse;
use Psr\Http\Message\ResponseInterface;

use Facade\IgnitionContracts\ProvidesSolution;
use Facade\IgnitionContracts\Solution;
use Facade\IgnitionContracts\BaseSolution;

class RenderException extends Exception implements ProvidesSolution
{
    use ParsesResponse;

    /** @var string */
    protected $message;

    /** @var string */
    protected $description;

    protected $errors = [
        'ERR_ACCESS_DENIED',
        'ERR_ADDRESS_INVALID',
        'ERR_ADDRESS_IN_USE',
        'ERR_ADDRESS_UNREACHABLE',
        'ERR_ADD_USER_CERT_FAILED',
        'ERR_ALPN_NEGOTIATION_FAILED',
        'ERR_BAD_SSL_CLIENT_AUTH_CERT',
        'ERR_BLOCKED_BY_ADMINISTRATOR',
        'ERR_BLOCKED_BY_CLIENT',
        'ERR_BLOCKED_BY_CSP',
        'ERR_BLOCKED_BY_RESPONSE',
        'ERR_BLOCKED_ENROLLMENT_CHECK_PENDING',
        'ERR_CACHE_AUTH_FAILURE_AFTER_READ',
        'ERR_CACHE_CHECKSUM_MISMATCH',
        'ERR_CACHE_CHECKSUM_READ_FAILURE',
        'ERR_CACHE_CREATE_FAILURE',
        'ERR_CACHE_DOOM_FAILURE',
        'ERR_CACHE_ENTRY_NOT_SUITABLE',
        'ERR_CACHE_LOCK_TIMEOUT',
        'ERR_CACHE_MISS',
        'ERR_CACHE_OPEN_FAILURE',
        'ERR_CACHE_OPEN_OR_CREATE_FAILURE',
        'ERR_CACHE_OPERATION_NOT_SUPPORTED',
        'ERR_CACHE_RACE',
        'ERR_CACHE_READ_FAILURE',
        'ERR_CACHE_WRITE_FAILURE',
        'ERR_CERTIFICATE_TRANSPARENCY_REQUIRED',
        'ERR_CERT_AUTHORITY_INVALID',
        'ERR_CERT_COMMON_NAME_INVALID',
        'ERR_CERT_CONTAINS_ERRORS',
        'ERR_CERT_DATABASE_CHANGED',
        'ERR_CERT_DATE_INVALID',
        'ERR_CERT_END',
        'ERR_CERT_ERROR_IN_SSL_RENEGOTIATION',
        'ERR_CERT_INVALID',
        'ERR_CERT_KNOWN_INTERCEPTION_BLOCKED',
        'ERR_CERT_NAME_CONSTRAINT_VIOLATION',
        'ERR_CERT_NON_UNIQUE_NAME',
        'ERR_CERT_NO_REVOCATION_MECHANISM',
        'ERR_CERT_REVOKED',
        'ERR_CERT_SYMANTEC_LEGACY',
        'ERR_CERT_UNABLE_TO_CHECK_REVOCATION',
        'ERR_CERT_VALIDITY_TOO_LONG',
        'ERR_CERT_WEAK_KEY',
        'ERR_CERT_WEAK_SIGNATURE_ALGORITHM',
        'ERR_CLEARTEXT_NOT_PERMITTED',
        'ERR_CLIENT_AUTH_CERT_TYPE_UNSUPPORTED',
        'ERR_CONNECTION_ABORTED',
        'ERR_CONNECTION_CLOSED',
        'ERR_CONNECTION_FAILED',
        'ERR_CONNECTION_REFUSED' => 'The url you are attempting to render is probably not publicly accesible.',
        'ERR_CONNECTION_RESET',
        'ERR_CONNECTION_TIMED_OUT',
        'ERR_CONTENT_DECODING_FAILED',
        'ERR_CONTENT_DECODING_INIT_FAILED',
        'ERR_CONTENT_LENGTH_MISMATCH',
        'ERR_CONTEXT_SHUT_DOWN',
        'ERR_CT_CONSISTENCY_PROOF_PARSING_FAILED',
        'ERR_CT_STH_INCOMPLETE',
        'ERR_CT_STH_PARSING_FAILED',
        'ERR_DISALLOWED_URL_SCHEME',
        'ERR_DNS_CACHE_MISS',
        'ERR_DNS_MALFORMED_RESPONSE',
        'ERR_DNS_SEARCH_EMPTY',
        'ERR_DNS_SECURE_RESOLVER_HOSTNAME_RESOLUTION_FAILED',
        'ERR_DNS_SERVER_FAILED',
        'ERR_DNS_SERVER_REQUIRES_TCP',
        'ERR_DNS_SORT_ERROR',
        'ERR_DNS_TIMED_OUT',
        'ERR_EARLY_DATA_REJECTED',
        'ERR_EMPTY_RESPONSE',
        'ERR_ENCODING_CONVERSION_FAILED',
        'ERR_ENCODING_DETECTION_FAILED',
        'ERR_FAILED',
        'ERR_FILE_EXISTS',
        'ERR_FILE_NOT_FOUND',
        'ERR_FILE_NO_SPACE',
        'ERR_FILE_PATH_TOO_LONG',
        'ERR_FILE_TOO_BIG',
        'ERR_FILE_VIRUS_INFECTED',
        'ERR_FTP_BAD_COMMAND_SEQUENCE',
        'ERR_FTP_COMMAND_NOT_SUPPORTED',
        'ERR_FTP_FAILED',
        'ERR_FTP_FILE_BUSY',
        'ERR_FTP_SERVICE_UNAVAILABLE',
        'ERR_FTP_SYNTAX_ERROR',
        'ERR_FTP_TRANSFER_ABORTED',
        'ERR_H2_OR_QUIC_REQUIRED',
        'ERR_HOST_RESOLVER_QUEUE_TOO_LARGE',
        'ERR_HTTP2_CLAIMED_PUSHED_STREAM_RESET_BY_SERVER',
        'ERR_HTTP2_CLIENT_REFUSED_STREAM',
        'ERR_HTTP2_COMPRESSION_ERROR',
        'ERR_HTTP2_FLOW_CONTROL_ERROR',
        'ERR_HTTP2_FRAME_SIZE_ERROR',
        'ERR_HTTP2_INADEQUATE_TRANSPORT_SECURITY',
        'ERR_HTTP2_PING_FAILED',
        'ERR_HTTP2_PROTOCOL_ERROR',
        'ERR_HTTP2_PUSHED_RESPONSE_DOES_NOT_MATCH',
        'ERR_HTTP2_PUSHED_STREAM_NOT_AVAILABLE',
        'ERR_HTTP2_RST_STREAM_NO_ERROR_RECEIVED',
        'ERR_HTTP2_SERVER_REFUSED_STREAM',
        'ERR_HTTP2_STREAM_CLOSED',
        'ERR_HTTPS_PROXY_TUNNEL_RESPONSE_REDIRECT',
        'ERR_HTTP_1_1_REQUIRED',
        'ERR_HTTP_RESPONSE_CODE_FAILURE',
        'ERR_ICANN_NAME_COLLISION',
        'ERR_IMPORT_CA_CERT_FAILED',
        'ERR_IMPORT_CA_CERT_NOT_CA',
        'ERR_IMPORT_CERT_ALREADY_EXISTS',
        'ERR_IMPORT_SERVER_CERT_FAILED',
        'ERR_INCOMPLETE_CHUNKED_ENCODING',
        'ERR_INCOMPLETE_HTTP2_HEADERS',
        'ERR_INSECURE_RESPONSE',
        'ERR_INSUFFICIENT_RESOURCES',
        'ERR_INTERNET_DISCONNECTED',
        'ERR_INVALID_ARGUMENT',
        'ERR_INVALID_AUTH_CREDENTIALS',
        'ERR_INVALID_CHUNKED_ENCODING',
        'ERR_INVALID_HANDLE',
        'ERR_INVALID_HTTP_RESPONSE',
        'ERR_INVALID_REDIRECT',
        'ERR_INVALID_RESPONSE',
        'ERR_INVALID_SIGNED_EXCHANGE',
        'ERR_INVALID_URL',
        'ERR_INVALID_WEB_BUNDLE',
        'ERR_KEY_GENERATION_FAILED',
        'ERR_MALFORMED_IDENTITY',
        'ERR_MANDATORY_PROXY_CONFIGURATION_FAILED',
        'ERR_METHOD_NOT_SUPPORTED',
        'ERR_MISCONFIGURED_AUTH_ENVIRONMENT',
        'ERR_MISSING_AUTH_CREDENTIALS',
        'ERR_MSG_TOO_BIG',
        'ERR_NAME_NOT_RESOLVED',
        'ERR_NAME_RESOLUTION_FAILED',
        'ERR_NETWORK_ACCESS_DENIED',
        'ERR_NETWORK_CHANGED',
        'ERR_NETWORK_IO_SUSPENDED',
        'ERR_NOT_IMPLEMENTED',
        'ERR_NO_BUFFER_SPACE',
        'ERR_NO_PRIVATE_KEY_FOR_CERT',
        'ERR_NO_SSL_VERSIONS_ENABLED',
        'ERR_NO_SUPPORTED_PROXIES',
        'ERR_OUT_OF_MEMORY',
        'ERR_PAC_NOT_IN_DHCP',
        'ERR_PAC_SCRIPT_FAILED',
        'ERR_PAC_SCRIPT_TERMINATED',
        'ERR_PKCS12_IMPORT_BAD_PASSWORD',
        'ERR_PKCS12_IMPORT_FAILED',
        'ERR_PKCS12_IMPORT_INVALID_FILE',
        'ERR_PKCS12_IMPORT_INVALID_MAC',
        'ERR_PKCS12_IMPORT_UNSUPPORTED',
        'ERR_PRECONNECT_MAX_SOCKET_LIMIT',
        'ERR_PRIVATE_KEY_EXPORT_FAILED',
        'ERR_PROXY_AUTH_REQUESTED',
        'ERR_PROXY_AUTH_REQUESTED_WITH_NO_CONNECTION',
        'ERR_PROXY_AUTH_UNSUPPORTED',
        'ERR_PROXY_CERTIFICATE_INVALID',
        'ERR_PROXY_CONNECTION_FAILED',
        'ERR_PROXY_HTTP_1_1_REQUIRED',
        'ERR_QUIC_CERT_ROOT_NOT_KNOWN',
        'ERR_QUIC_GOAWAY_REQUEST_CAN_BE_RETRIED',
        'ERR_QUIC_HANDSHAKE_FAILED',
        'ERR_QUIC_PROTOCOL_ERROR',
        'ERR_READ_IF_READY_NOT_IMPLEMENTED',
        'ERR_REQUEST_RANGE_NOT_SATISFIABLE',
        'ERR_RESPONSE_BODY_TOO_BIG_TO_DRAIN',
        'ERR_RESPONSE_HEADERS_MULTIPLE_CONTENT_DISPOSITION',
        'ERR_RESPONSE_HEADERS_MULTIPLE_CONTENT_LENGTH',
        'ERR_RESPONSE_HEADERS_MULTIPLE_LOCATION',
        'ERR_RESPONSE_HEADERS_TOO_BIG',
        'ERR_RESPONSE_HEADERS_TRUNCATED',
        'ERR_SELF_SIGNED_CERT_GENERATION_FAILED',
        'ERR_SOCKET_IS_CONNECTED',
        'ERR_SOCKET_NOT_CONNECTED',
        'ERR_SOCKET_RECEIVE_BUFFER_SIZE_UNCHANGEABLE',
        'ERR_SOCKET_SEND_BUFFER_SIZE_UNCHANGEABLE',
        'ERR_SOCKET_SET_RECEIVE_BUFFER_SIZE_ERROR',
        'ERR_SOCKET_SET_SEND_BUFFER_SIZE_ERROR',
        'ERR_SOCKS_CONNECTION_FAILED',
        'ERR_SOCKS_CONNECTION_HOST_UNREACHABLE',
        'ERR_SSL_BAD_PEER_PUBLIC_KEY',
        'ERR_SSL_BAD_RECORD_MAC_ALERT',
        'ERR_SSL_CLIENT_AUTH_CERT_BAD_FORMAT',
        'ERR_SSL_CLIENT_AUTH_CERT_NEEDED',
        'ERR_SSL_CLIENT_AUTH_CERT_NO_PRIVATE_KEY',
        'ERR_SSL_CLIENT_AUTH_NO_COMMON_ALGORITHMS',
        'ERR_SSL_CLIENT_AUTH_PRIVATE_KEY_ACCESS_DENIED',
        'ERR_SSL_CLIENT_AUTH_SIGNATURE_FAILED',
        'ERR_SSL_DECOMPRESSION_FAILURE_ALERT',
        'ERR_SSL_DECRYPT_ERROR_ALERT',
        'ERR_SSL_HANDSHAKE_NOT_COMPLETED',
        'ERR_SSL_KEY_USAGE_INCOMPATIBLE',
        'ERR_SSL_NO_RENEGOTIATION',
        'ERR_SSL_OBSOLETE_CIPHER',
        'ERR_SSL_OBSOLETE_VERSION',
        'ERR_SSL_PINNED_KEY_NOT_IN_CERT_CHAIN',
        'ERR_SSL_PROTOCOL_ERROR',
        'ERR_SSL_RENEGOTIATION_REQUESTED',
        'ERR_SSL_SERVER_CERT_BAD_FORMAT',
        'ERR_SSL_SERVER_CERT_CHANGED',
        'ERR_SSL_UNRECOGNIZED_NAME_ALERT',
        'ERR_SSL_VERSION_OR_CIPHER_MISMATCH',
        'ERR_SYN_REPLY_NOT_RECEIVED',
        'ERR_TEMPORARILY_THROTTLED',
        'ERR_TIMED_OUT',
        'ERR_TLS13_DOWNGRADE_DETECTED',
        'ERR_TOO_MANY_REDIRECTS',
        'ERR_TOO_MANY_RETRIES',
        'ERR_TRUST_TOKEN_OPERATION_FAILED',
        'ERR_TRUST_TOKEN_OPERATION_SUCCESS_WITHOUT_SENDING_REQUEST',
        'ERR_TUNNEL_CONNECTION_FAILED',
        'ERR_UNABLE_TO_REUSE_CONNECTION_FOR_PROXY_AUTH',
        'ERR_UNDOCUMENTED_SECURITY_LIBRARY_STATUS',
        'ERR_UNEXPECTED',
        'ERR_UNEXPECTED_PROXY_AUTH',
        'ERR_UNEXPECTED_SECURITY_LIBRARY_STATUS',
        'ERR_UNKNOWN_URL_SCHEME',
        'ERR_UNRECOGNIZED_FTP_DIRECTORY_LISTING_FORMAT',
        'ERR_UNSAFE_PORT',
        'ERR_UNSAFE_REDIRECT',
        'ERR_UNSUPPORTED_AUTH_SCHEME',
        'ERR_UPLOAD_FILE_CHANGED',
        'ERR_UPLOAD_STREAM_REWIND_NOT_SUPPORTED',
        'ERR_WINSOCK_UNEXPECTED_WRITTEN_BYTES',
        'ERR_WRONG_VERSION_ON_EARLY_DATA',
        'ERR_WS_PROTOCOL_ERROR',
        'ERR_WS_THROTTLE_QUEUE_TOO_LARGE',
        'ERR_WS_UPGRADE',
    ];

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $message = $this->parseResponse($response);
        $description = null;

        if (!is_object($message)) {
            $error = json_decode('{' . Str::beforeLast(Str::after($message, '{'), '}') . '}');

            if (json_last_error() === JSON_ERROR_NONE && isset($error->message)) {
                $message = $error->message;
            }
        } else {
            if ((property_exists($message, 'error') || property_exists($message, 'type')) && property_exists($message, 'message')) {
                $description = $message->message;
                $description = Str::replace('net::', '', $description);
                $message = $message->error ?? $message->type;

                foreach ($this->errors as $key => $value) {
                    $code = is_string($key) ? $key : $value;
                    $helper = $value;

                    if (Str::contains($description, $code)) {
                        $message = $description;
                        $description = $helper;
                        break;
                    }
                }
            }
        }

        if (!is_string($message)) {
            $message = json_encode($message, JSON_PRETTY_PRINT);
        }

        $message = str_replace('double', 'float', $message);
        $description = str_replace('double', 'float', $description);

        parent::__construct($message);

        $this->message = $message;
        $this->description = $description;
    }

    public function getSolution(): Solution
    {
        return BaseSolution::create('RenderException')
            ->setSolutionDescription($this->description)
            ->setDocumentationLinks([
                'Netflex Renderer documentation' => 'https://github.com/netflex-sdk/renderer#readme',
            ]);
    }
}
