<?php
declare(strict_types=1);

namespace GuzabaPlatform\Tests\Controllers;


use Guzaba2\Database\Interfaces\ConnectionInterface;
use Guzaba2\Http\Method;
use Guzaba2\Transaction\Transaction;
use GuzabaPlatform\Platform\Application\BaseTestController;
use GuzabaPlatform\Platform\Application\MysqlConnectionCoroutine;
use Guzaba2\Event\Event;
use Psr\Http\Message\ResponseInterface;

/**
 * Class TestTransactionRollbackReason
 * @package GuzabaPlatform\Tests\Controllers
 *
 * Tests does the @see Transaction::get_rollback_reason() shows correct in the various rollback cases.
 */
class TestTransactionRollbackReason extends BaseTestController
{
    protected const CONFIG_DEFAULTS = [
        'routes'        => [
            '/tests/transaction/rollback/reason/implicit/conn-ref'          => [
                Method::HTTP_PUT                                                => [self::class, 'test_implicit_rollback_conn_ref'],
            ],
            '/tests/transaction/rollback/reason/implicit/trans-ref'         => [
                Method::HTTP_PUT                                                => [self::class, 'test_implicit_rollback_trans_ref'],
            ],
            '/tests/transaction/rollback/reason/explicit'                   => [
                Method::HTTP_PUT                                                => [self::class, 'test_explicit_rollback'],
            ],
            '/tests/transaction/rollback/reason/exception'                  => [
                Method::HTTP_PUT                                                => [self::class, 'test_exception_rollback'],
            ],
            '/tests/transaction/rollback/reason/parent'                     => [
                Method::HTTP_PUT                                                => [self::class, 'test_parent_rollback'],
            ],
        ],
        'services' => [
            'ConnectionFactory',
        ]

    ];

    protected const CONFIG_RUNTIME = [];

    public function test_implicit_rollback_conn_ref(): ResponseInterface
    {
        $struct = ['total_events' => 1];
        $struct['events'] = [];
        $this->implicit_rollback_conn_ref($struct);
        return self::get_test_response($struct);
    }

    protected function implicit_rollback_conn_ref(&$struct): void
    {
        /** @var \Guzaba2\Database\Transaction $Transaction */
        $Transaction = self::get_service('ConnectionFactory')->get_connection(MysqlConnectionCoroutine::class, $CR)->new_transaction($TR);
        $Transaction->add_callback('_before_rollback', function(Event $Event) use (&$struct): void {
            //the rollback reason is set before the _before_rollback event is fired
            /** @var \Guzaba2\Database\Transaction $Transaction */
            $Transaction = $Event->get_subject();
            $rollback_reason = $Transaction->get_rollback_reason();
            if ($rollback_reason === $Transaction::ROLLBACK_REASON['IMPLICIT']) {
                $struct['events'][1] = 'rollback reason is IMPLICIT';
            }
        });
        $Transaction->begin();
    }

    public function test_implicit_rollback_trans_ref(): ResponseInterface
    {
        $struct = ['total_events' => 1];
        $struct['events'] = [];
        $this->implicit_rollback_trans_ref($struct);
        return self::get_test_response($struct);
    }

    protected function implicit_rollback_trans_ref(&$struct): void
    {
        /** @var \Guzaba2\Database\Transaction $Transaction */
        $Transaction = self::get_service('ConnectionFactory')->get_connection(MysqlConnectionCoroutine::class, $CR)->new_transaction($TR);
        $Transaction->add_callback('_before_rollback', function(Event $Event) use (&$struct): void {
            //the rollback reason is set before the _before_rollback event is fired
            /** @var \Guzaba2\Database\Transaction $Transaction */
            $Transaction = $Event->get_subject();
            $rollback_reason = $Transaction->get_rollback_reason();
            if ($rollback_reason === $Transaction::ROLLBACK_REASON['IMPLICIT']) {
                $struct['events'][1] = 'rollback reason is IMPLICIT';
            }
        });
        $Transaction->begin();
        unset($TR);//explicitly trigger the transaction scope reference destruction (before the connection ref destruction)
    }

    public function test_explicit_rollback(): ResponseInterface
    {
        /** @var \Guzaba2\Database\Transaction $Transaction */
        $Transaction = self::get_service('ConnectionFactory')->get_connection(MysqlConnectionCoroutine::class, $CR)->new_transaction($TR);
        $Transaction->add_callback('_before_rollback', function(Event $Event) use (&$struct): void {
            //the rollback reason is set before the _before_rollback event is fired
            /** @var \Guzaba2\Database\Transaction $Transaction */
            $Transaction = $Event->get_subject();
            $rollback_reason = $Transaction->get_rollback_reason();
            if ($rollback_reason === $Transaction::ROLLBACK_REASON['EXPLICIT']) {
                $struct['events'][1] = 'rollback reason is IMPLICIT';
            }
        });
        $Transaction->begin();
        $Transaction->rollback();
    }

    public function test_exception_rollback(): ResponseInterface
    {

    }

    protected function exception_rollback(): void
    {

    }

    public function test_parent_rollback(): ResponseInterface
    {

    }


}