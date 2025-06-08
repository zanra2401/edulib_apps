package com.edulib.edulib;

import org.junit.jupiter.api.Test;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.data.jpa.repository.config.EnableJpaRepositories;

@SpringBootTest
@EnableJpaRepositories(basePackages = "com.edulib.edulib.repository")
class EdulibBackendApplicationTests {

	@Test
	void contextLoads() {
	}

}
