<?php

namespace App\EstructurasDeDatos\ListaEnlazada;

class LinkedList implements Iterator{
    private $head;    
    private $size;
    private $current;  
    private $currentKey;  

    public function __construct() {
        $this->head = null;
        $this->size = 0;
        $this->current = null;
        $this->currentKey = 0;
    }

    // Método para obtener el tamaño de la lista
    public function getSize() {
        return $this->size;
    }

    // Método para verificar si la lista está vacía
    public function isEmpty() {
        return $this->size === 0;
    }

    // Método para agregar un elemento al final de la lista
    public function append($data) {
        $newNode = new Node($data);
        if ($this->isEmpty()) {
            $this->head = $newNode;
        } else {
            $current = $this->head;
            while ($current->next !== null) {
                $current = $current->next;
            }
            $current->next = $newNode;
        }
        $this->size++;
    }

    // Método para imprimir los elementos de la lista
    public function display() {
        if ($this->isEmpty()) {
            echo "La lista está vacía.\n";
            return;
        }
        $current = $this->head;
        while ($current !== null) {
            echo $current->data . " -> ";
            $current = $current->next;
        }
        echo "null\n";
        echo "Tamaño de la lista: " . $this->size . "\n";
    }

    // Método para eliminar un nodo por valor
    public function delete($data) {
        if ($this->isEmpty()) {
            echo "La lista está vacía. No se puede eliminar.\n";
            return;
        }

        // Si el nodo a eliminar es el primero
        if ($this->head->data === $data) {
            $this->head = $this->head->next;
            $this->size--; // Reducir el tamaño de la lista
            return;
        }

        $current = $this->head;
        while ($current->next !== null && $current->next->data !== $data) {
            $current = $current->next;
        }

        if ($current->next === null) {
            echo "El valor $data no se encontró en la lista.\n";
        } else {
            $current->next = $current->next->next;
            $this->size--; // Reducir el tamaño de la lista
        }
    }

    public function rewind() {
        $this->current = $this->head;
        $this->currentKey = 0;
    }

    // Devuelve el elemento actual
    public function current() {
        return $this->current->data;
    }

    // Devuelve la clave del elemento actual (en este caso, usamos índices simulados)
    public function key() {
        return $this->currentKey;
    }

    // Avanza al siguiente nodo
    public function next() {
        $this->current = $this->current->next;
        $this->currentKey++;
    }

    // Verifica si el nodo actual es válido
    public function valid() {
        return $this->current !== null;
    }
}