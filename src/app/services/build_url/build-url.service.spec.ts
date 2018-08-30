import { TestBed, inject } from '@angular/core/testing';

import { BuildUrlService } from './build-url.service';

describe('BuildUrlService', () => {
  beforeEach(() => {
    TestBed.configureTestingModule({
      providers: [BuildUrlService]
    });
  });

  it('should be created', inject([BuildUrlService], (service: BuildUrlService) => {
    expect(service).toBeTruthy();
  }));
});
