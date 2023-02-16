<?php
/**
 * MB "Vienas bitas" (Magetrend.com)
 *
 * @category MageTrend
 * @package  Magetend/Scheduler
 * @author   Edvinas St. <edwin@magetrend.com>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     http://www.magetrend.com/magento-2-scheduler
 */

namespace Magetrend\Scheduler\Model;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magetrend\Scheduler\Api\Data\ScheduleInterface;
use Magetrend\Scheduler\Api\ScheduleManagementInterface;
use Magetrend\Scheduler\Api\ScheduleRepositoryInterface;

/**
 * Schedule management class
 */
class ScheduleManagement implements ScheduleManagementInterface
{
    /**
     * @var ScheduleRepositoryInterface
     */
    public $scheduleRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    public $searchCriteriaBuilder;

    /**
     * ScheduleManagement constructor.
     * @param ScheduleRepositoryInterface $scheduleRepository
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        \Magetrend\Scheduler\Api\ScheduleRepositoryInterface $scheduleRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->scheduleRepository = $scheduleRepository;
    }

    /**
     * Update schedule data
     * @param array $data
     * @param null $scheduleId
     * @return ScheduleInterface
     */
    public function save($data, $scheduleId = null)
    {
        if ($scheduleId == null) {
            $model = $this->scheduleRepository->getNew();
            $model->setStatus(ScheduleInterface::STATUS_SCHEDULED);
        } else {
            $model = $this->scheduleRepository->get($scheduleId);
        }

        $data = $this->prepareData($data);
        $model->addData($data);
        $model->loadPost($data);
        $this->scheduleRepository->save($model);
        return $this->scheduleRepository->save($model);
    }

    /**
     * Duplicate schedule
     * @param $id
     * @return Schedule
     */
    public function duplicate($id)
    {
        /**
         * @var Schedule $schedule
         */
        $schedule = $this->scheduleRepository->get($id);
        $schedule->getInstance()->beforeClone();
        $schedule->setName($schedule->getName().' (Cloned)')
            ->setData(ScheduleInterface::IS_ACTIVE, 0)
            ->setData(ScheduleInterface::DATE_FROM, null)
            ->setData(ScheduleInterface::DATE_TO, null)
            ->setData(ScheduleInterface::STATUS, ScheduleInterface::STATUS_SCHEDULED)
            ->setData(ScheduleInterface::STORE_IDS, $schedule->getStores())
            ->setData('entity_id', null);

        $this->scheduleRepository->save($schedule);
        return $schedule;
    }

    /**
     * Prepare date for save
     * @param $data
     * @return mixed
     */
    public function prepareData($data)
    {
        if (isset($data['product_ids']) && !empty($data['product_ids'])) {
            $data['product_ids'] = array_keys(json_decode($data['product_ids'], true));
        }

        if (isset($data['rule']['conditions'])) {
            $data['conditions'] = $data['rule']['conditions'];
        }

        unset($data['rule']);

        return $data;
    }

    /**
     * Validate data before save
     * @param $data
     * @throws LocalizedException
     */
    public function validateData($data)
    {
        if (!isset($data[ScheduleInterface::DATE_FROM])) {
            throw new LocalizedException(__('You must to choose schedule starting date'));
        }

        if (strtotime($data[ScheduleInterface::DATE_FROM]) <= time()) {
            throw new LocalizedException(__('The schedule start date has to be future date'));
        }
    }

    /**
     * Mass schedule update
     * @param $ids
     * @param $function
     * @return bool
     */
    public function massAction($ids, $function)
    {
        if (empty($ids)) {
            return true;
        }

        $collection = $this->getCollectionByIds($ids);
        if ($collection->getTotalCount() == 0) {
            return true;
        }

        foreach ($collection->getItems() as $schedule) {
            $function($schedule);
            $this->scheduleRepository->save($schedule);
        }

        return true;
    }

    /**
     * Mass schedule delete
     * @param $ids
     * @return bool
     */
    public function massDelete($ids)
    {
        if (empty($ids)) {
            return true;
        }

        foreach ($ids as $id) {
            $this->scheduleRepository->delete($id);
        }

        return true;
    }

    /**
     * Returns schedule collection by ids for mass actions
     * @param $ids
     * @return \Magetrend\Scheduler\Api\Data\ScheduleSearchResultsInterface
     */
    public function getCollectionByIds($ids)
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter('entity_id', $ids, 'in')
            ->create();
        $collection = $this->scheduleRepository->getList($searchCriteria);

        return $collection;
    }
}
