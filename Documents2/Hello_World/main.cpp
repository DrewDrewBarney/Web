/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/* 
 * File:   main.cpp
 * Author: drew
 *
 * Created on 9 janvier 2022, 10:30
 */

#include <iostream>
#include <cstdlib>

#define arraySize(a) (sizeof(a) / sizeof((a)[0]))

using namespace std;

/*
 * 
 */


int size(void* arrayPtr){
    void* itemPtr = 0;
    return sizeof(arrayPtr);
    return sizeof(itemPtr);
    return sizeof(arrayPtr) / sizeof(itemPtr);
}



int main(int argc, char** argv) {
    
    const char* stringArray[] = {"one", "two", "three", "four"};

    for (int i = 0 ; i < arraySize(stringArray) ; i++){
        cout << *(stringArray+i) << endl;
    }
        
    return 0;
}

