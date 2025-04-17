#include <iostream>
#include <cstdlib>
#include <cstring>
#include <ctime>
#include <iomanip>
#include <fstream>

void outOfPreResults(){
    char* cookie = getenv("HTTP_COOKIE");
    if (cookie != nullptr) {
        double result = 0;
        char date[100];
        char *token = strtok(cookie, ";");

        while(token != nullptr){
            if(strstr(token, "last_result=")) {
                token += 12;
                result = atof(token);
            }

            else if(strstr(token, "last_date=")) {
                token += 10;
                strcpy(date, token);
            }
            
            token = strtok(nullptr, ";");
        }

        std::cout << "Предыдущий результат: (" << result << " | 3) : " << date; 
    }

    else std::cout << "Предыдущих попыток не было.";
}

void outOfResults(double points, char *method, char *datetime){
    std::cout << "Set-Cookie: last_result=" << points << "; path=/;\n";
    std::cout << "Set-Cookie: last_date=" << datetime << "; path=/;\n"; 
    std::cout << "Content-type: text/html\n\n";
    std::cout << "<html lang=\"ru\">";
    std::cout << "<head> <meta charset=\"UTF-8\"></head>";
    std::cout << "<body><h1> Результат пройденного теста: " << points << " из 3" << " баллов" << "</h1>";
    std::cout << "<h1> Дата и время прохождения: " << datetime << "</h1>";
    std::cout << "<h1> Используемый метод: " << method << "</h1>";
    std::cout << "<h1> Предыдущие попытки: </h1><h2>";
    outOfPreResults();
    std::cout << "</h2></body></html>";
}

void calcOfResult(double *points, char *query_string){
    double que1 = 0, que2 = 0, que3 = 0;

    if (query_string != nullptr) {
        char *token = strtok(query_string, "&");

        while(token != nullptr){
            if (strstr(token, "choice=")){
                token += 7;
                que1 += atof(token);
            }
            else if(strstr(token, "checkOne=")){
                token += 9;
                que2 += atof(token);
            }
            else if (strstr(token, "checkTwo=")){
                token += 9;
                que3 += atof(token);
            }

            token = strtok(nullptr, "&");
        }
    }

    if (que1 == 1) (*points)++;
    
    if (que2 == 6) (*points)++;
    else if (que2 == 1 || que2 == 5 || que2 == 9) *points += 0.5;

    if (que3 == 4) (*points)++;
    else if(que3 == 1 || que3 == 3 || que3 == 9) *points += 0.5;
}

int main() {
    time_t now = time(0);       
    tm *ltm = localtime(&now);
    char datetime[100];
    snprintf(datetime, sizeof(datetime), "%02d.%02d.%d %02d:%02d:%02d",
             ltm->tm_mday,
             ltm->tm_mon + 1,
             1900 + ltm->tm_year,
             ltm->tm_hour,
             ltm->tm_min,
             ltm->tm_sec);

    double points = 0;
    char *query_string=new char[256];
    char *method = getenv("REQUEST_METHOD");

    if (method) {
        if (strcmp(method, "GET") == 0) {
            query_string=getenv("QUERY_STRING");
        } else if (strcmp(method, "POST") == 0) {
            char *len_str = getenv("CONTENT_LENGTH");
            int len = len_str ? atoi(len_str) : 0;
            std::cin.read(query_string, len);
            query_string[len] = '\0';
        }
    }

    calcOfResult(&points, query_string);

    outOfResults(points, method, datetime);

    return 0;
}