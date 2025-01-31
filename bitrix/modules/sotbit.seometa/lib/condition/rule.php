<?
namespace Sotbit\Seometa\Condition;

use Sotbit\Seometa\Property\PropertyCollection;
use Sotbit\Seometa\Property\PropertySet;
use Sotbit\Seometa\Property\PropertySetCollection;
use Sotbit\Seometa\Property\PropertySetEntity;

class Rule {
    public function parse(Condition $condition) {
        $rule = unserialize($condition->RULE);
        $cond = new \Sotbit\Seometa\Helper\Condition();
        $openCond = $cond->openGroups($rule);
        return $this->mapPropertySet($openCond['CHILDREN']);
    }

    public function filterRuleCombination(array &$parserdRule, PropertyCollection $propertyCollection) {
//        for
    }

    private function mapPropertySet(array &$conditions) {
        $propertySetCollection = new PropertySetCollection();

        foreach ($conditions as $conditionSet) {
            $propertySet = new PropertySet();
            if($conditionSet) {
                foreach ($conditionSet as $condition) {
                    $propertySetEntity = new PropertySetEntity($condition);
                    $propertySet->add($propertySetEntity);
                }

                $propertySetCollection->addSet($propertySet);
            } else {
                return null;
            }
        }

        return $propertySetCollection;
    }
}