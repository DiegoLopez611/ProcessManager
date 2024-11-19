<?php

namespace App\EstructurasDeDatos\Pila;

class Stack {
    private $top;
    private $size;

    public function __construct() {
        $this->top = null;
        $this->size = 0;
    }

    // Método para verificar si la pila está vacía
    public function isEmpty() {
        return $this->top === null;
    }

    // Método para obtener el tamaño de la pila
    public function getSize() {
        return $this->size;
    }

    // Método para agregar un elemento a la pila
    public function push($data) {
        $newNode = new Node($data);
        $newNode->next = $this->top; 
        $this->top = $newNode;       
        $this->size++;               
    }

    // Método para eliminar y devolver el elemento en la cima de la pila
    public function pop() {
        if ($this->isEmpty()) {
            throw new Exception("La pila está vacía. No se puede realizar pop.");
        }
        $poppedNode = $this->top;    
        $this->top = $this->top->next; 
        $this->size--;               
        return $poppedNode->data;  
    }

    // Método para ver el elemento en la cima sin eliminarlo
    public function peek() {
        if ($this->isEmpty()) {
            throw new Exception("La pila está vacía. No se puede realizar peek.");
        }
        return $this->top->data; // Devolvemos el dato del nodo en la cima
    }

    // Método para imprimir todos los elementos de la pila
    public function display() {
        if ($this->isEmpty()) {
            echo "La pila está vacía.\n";
            return;
        }

        $current = $this->top;
        echo "Pila (de cima a base):\n";
        while ($current !== null) {
            echo $current->data . "\n";
            $current = $current->next;
        }
    }
}