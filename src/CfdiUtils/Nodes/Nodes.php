<?php
namespace CfdiUtils\Nodes;

class Nodes implements \Countable, \IteratorAggregate
{
    /** @var NodeInterface[] */
    private $nodes = [];

    /**
     * Nodes constructor.
     * @param NodeInterface[] $nodes
     */
    public function __construct(array $nodes = [])
    {
        $this->importFromArray($nodes);
    }

    public function add(NodeInterface ...$nodes): self
    {
        foreach ($nodes as $node) {
            if (! $this->exists($node)) {
                $this->nodes[] = $node;
            }
        }
        return $this;
    }

    public function indexOf(NodeInterface $node): int
    {
        if (false === $index = array_search($node, $this->nodes, true)) {
            $index = -1;
        }
        return (int) $index;
    }

    public function remove(NodeInterface $node): self
    {
        $index = $this->indexOf($node);
        if ($index >= 0) {
            unset($this->nodes[$index]);
        }
        return $this;
    }

    public function removeAll(): self
    {
        $this->nodes = [];
        return $this;
    }

    public function exists(NodeInterface $node): bool
    {
        return ($this->indexOf($node) >= 0);
    }

    /**
     * @return NodeInterface|null
     */
    public function first()
    {
        foreach ($this->nodes as $node) {
            return $node;
        }
        return null;
    }

    /**
     * @param int $index
     * @return NodeInterface|null
     */
    public function get(int $index)
    {
        /** @var NodeInterface[] $nodesByPosition */
        $nodesByPosition = array_values($this->nodes);
        return (array_key_exists($index, $nodesByPosition)) ? $nodesByPosition[$index] : null;
    }

    /**
     * @param string $nodeName
     * @return NodeInterface|null
     */
    public function firstNodeWithName(string $nodeName)
    {
        foreach ($this->nodes as $node) {
            if ($node->name() === $nodeName) {
                return $node;
            }
        }
        return null;
    }

    public function getNodesByName(string $nodeName): Nodes
    {
        $nodes = new self();
        foreach ($this->nodes as $node) {
            if ($node->name() === $nodeName) {
                $nodes->add($node);
            }
        }
        return $nodes;
    }

    /**
     * @param NodeInterface[] $nodes
     * @return Nodes
     */
    public function importFromArray(array $nodes): self
    {
        foreach ($nodes as $index => $node) {
            if (! ($node instanceof NodeInterface)) {
                throw new \InvalidArgumentException("The element index $index is not a NodeInterface object");
            }
            $this->add($node);
        }
        return $this;
    }

    /**
     * @return \ArrayIterator|\Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->nodes);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->nodes);
    }
}
