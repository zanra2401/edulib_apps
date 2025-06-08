package com.edulib.edulib;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;
import org.springframework.scheduling.annotation.EnableScheduling;

@SpringBootApplication
@EnableScheduling

public class EdulibBackendApplication {

	public static void main(String[] args) {
		SpringApplication.run(EdulibBackendApplication.class, args);
	}

}
