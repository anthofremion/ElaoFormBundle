<?php

namespace Elao\Bundle\FormBundle\Service;

use Symfony\Component\Form\FormView;

use Elao\Bundle\FormBundle\Model\FormTree;
use Elao\Bundle\FormBundle\Model\FormTreeNode;

/**
 * Responsible form building tree for forms.
 */
class FormTreeBuilder
{
    /**
     * Form type with no children labels
     *
     * @var array
     */
    private $noChildren = array('date', 'time', 'datetime', 'choice');

    /**
     * Get the full tree for a given view
     *
     * @param  FormView $view
     *
     * @return array
     */
    public function getTree(FormView $view)
    {
        if ($view->parent !== null) {
            $tree = $this->getTree($view->parent);
        } else {
            $tree = new FormTree;
        }

        $tree->addChild($this->createNodeFromView($view));

    	return $tree;
    }

    /**
     * Set form type that should not be treated as having children
     *
     * @param array $types
     */
    public function setNoChildren(array $types)
    {
        $this->noChildren = $types;
    }

    /**
     * Create a FormTreeNode for the given view
     *
     * @param  FormView $view
     *
     * @return FormTreeNode
     */
    private function createNodeFromView(FormView $view)
    {
        $haschildren  = $this->hasChildrenWithLabel($view);
        $isCollection = $haschildren ? $this->isCollection($view) : false;
        $isPrototype  = $this->isPrototype($view);

        return new FormTreeNode($view->vars['name'], $haschildren, $isCollection, $isPrototype);
    }

    /**
     * Test if the given form view has children with labels
     *
     * @param FormView $view
     *
     * @return boolean
     */
    private function hasChildrenWithLabel(FormView $view)
    {
    	if ($view->parent === null || !isset($view->vars['compound']) || !$view->vars['compound']) {
            return false;
    	}

    	foreach ($view->vars['block_prefixes'] as $prefix) {
            if (in_array($prefix, $this->noChildren)) {
                return false;
            }
    	}

    	return true;
    }

    /**
     * Test if the given form view is a collection
     *
     * @param FormView $view
     *
     * @return boolean
     */
    private function isCollection(FormView $view)
    {
        if ($view->parent === null || !$view->vars['compound']) {
            return false;
        }

        return in_array('collection', $view->vars['block_prefixes']);
    }

    /**
     * Test if the given form view is a prototype in a collection
     *
     * @param FormView $view
     *
     * @return boolean
     */
    private function isPrototype(FormView $view)
    {
        return $view->parent && $this->isCollection($view->parent);
    }
}