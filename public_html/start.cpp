#include <iostream>
#include <fstream>
#include <string>
#include <map>
#include <array>
#include <vector>
#include <sstream>
#include <iterator>
#include <unistd.h>
using namespace std;

/* struct PARTICLE {   // Declare PERSON struct type
    int m1;
    int m2;
    int n;
    int counter;
};   // Define object of type PERSON */

int main() 
{ 
    string row;
    string::size_type sz;
    map<int, vector<int>> particles;
    
    cout << "New world!" << endl;
    particles[1] = vector<int>({2, 0, 0, 0});
    particles[2] = vector<int>({1, 0, 0, 0});
    
    /* ifstream file ("particles");
    if (file.is_open()) {
        while ( getline (file,row) ) {

          istringstream iss(row);
          vector<string> results((istream_iterator<string>(iss)), istream_iterator<string>());          
          
          int i0 = std::stoi (results[0],&sz);
          int i1 = std::stoi (results[1],&sz);
          int i2 = std::stoi (results[2],&sz);
          
          particles[i0] = vector<int>({i1, i2, 0, 0});          
        }
    } else cout << "Unable to open file"; */
    
    int y;
    int max;
    
    cin >> max;
    y = 0;
    
    while (y < max) {
        y++;
    
        for( const auto& pair : particles ) {
             int id;
             int m1;
             int m2;
             int n;
             int new_id;
             
             id = pair.first;
             m1 = pair.second[0];
             m2 = pair.second[1];
             
             if (!pair.second[2]) {
                 n  = particles[m1][0];
                 particles[m1][3]++;
                 if (n == m1 || n == m2 || n == id) {
                     // create particle
                     new_id = particles.rbegin()->first + 1;
                     particles[new_id] = vector<int>({id, 0, m1, 1});
                     particles[id][2] = new_id;
                 } else {
                     // set new
                     particles[id][2] = n;
                 }
             }
        }    
        
        /* for( const auto& pair : particles ) {
            std::cout << "key: " << pair.first << "  value: [  " ;
            for( double d : pair.second ) std::cout << d << "  " ;
            std::cout << "]\n" ;
        } */
        
        cout << endl;
        cout << endl;
        
        for( const auto& pair : particles ) {
             int id;
             int m1;
             int m2;
             int n;
             
             id = pair.first;
             m1 = pair.second[0];
             m2 = pair.second[1];
             n  = pair.second[2];
             
             particles[id][0] = n;
             particles[id][1] = m1;
             particles[id][2] = 0;
        }
        
        /* for( const auto& pair : particles ) {
            std::cout << "key: " << pair.first << "  value: [  " ;
            for( double d : pair.second ) {
                std::cout << d << "  " ;
            }
            std::cout << "]\n" ;
        } */
        
        int z = 0;        
        ofstream file;
        file.open ("particles", ofstream::out | ofstream::trunc);
        file << "<?php $a=[\n";
        
        for( const auto& pair : particles ) {
            z++;
            file << pair.first << "=>[" << pair.second[0] << "," << pair.second[3] << "],\n";
            particles[pair.first][3] = 0;
        }
        
        file << "];";
        file.close();
        cout << "particles update, step = " << y << " count = " << z;
        
        //sleep(1);
    }
    
    return 0; 
}