import { Angular3pukuPage } from './app.po';

describe('angular3puku App', function() {
  let page: Angular3pukuPage;

  beforeEach(() => {
    page = new Angular3pukuPage();
  });

  it('should display message saying app works', () => {
    page.navigateTo();
    expect(page.getParagraphText()).toEqual('app works!');
  });
});
